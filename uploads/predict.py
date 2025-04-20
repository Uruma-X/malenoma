import sys
import os
import numpy as np
from PIL import Image
import tensorflow as tf
import logging
import traceback

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler("prediction.log"),
        logging.StreamHandler()
    ]
)
logger = logging.getLogger(__name__)

# === CONFIGURATION ===
MODEL_PATH = 'melanoma_model.h5'
IMAGE_SIZE = (224, 224)
CLASS_NAMES = ['akiec', 'bcc', 'bkl', 'df', 'mel', 'nv', 'vasc']
CLASS_DESCRIPTIONS = {
    'akiec': 'Actinic Keratosis / Intraepithelial Carcinoma',
    'bcc': 'Basal Cell Carcinoma',
    'bkl': 'Benign Keratosis',
    'df': 'Dermatofibroma',
    'mel': 'Melanoma',
    'nv': 'Melanocytic Nevus',
    'vasc': 'Vascular Lesion'
}

def load_model(model_path):
    """Load the model with error handling"""
    try:
        logger.info(f"Loading model from {model_path}")
        if not os.path.exists(model_path):
            logger.error(f"Model file not found at {model_path}")
            return None
            
        model = tf.keras.models.load_model(model_path)
        logger.info("Model loaded successfully")
        return model
    except Exception as e:
        logger.error(f"Error loading model: {str(e)}")
        logger.error(traceback.format_exc())
        return None

def preprocess_image(image_path):
    """Preprocess image with error handling"""
    try:
        if not os.path.exists(image_path):
            logger.error(f"Image file not found: {image_path}")
            return None
            
        logger.info(f"Processing image: {image_path}")
        img = Image.open(image_path)
        
        # Check if image is valid
        img.verify()
        
        # Reopen after verify
        img = Image.open(image_path)
        
        # Convert to RGB (in case of grayscale or RGBA)
        img = img.convert('RGB')
        
        # Resize to expected dimensions
        img = img.resize(IMAGE_SIZE)
        
        # Convert to numpy array and normalize
        img_array = np.array(img) / 255.0
        
        # Add batch dimension
        img_array = np.expand_dims(img_array, axis=0)
        
        logger.info(f"Image processed successfully: shape={img_array.shape}")
        return img_array
        
    except Exception as e:
        logger.error(f"Error processing image: {str(e)}")
        logger.error(traceback.format_exc())
        return None

def predict(model, img_array):
    """Make prediction with error handling"""
    try:
        if model is None or img_array is None:
            logger.error("Cannot make prediction: model or image is None")
            return None
            
        logger.info("Making prediction...")
        predictions = model.predict(img_array)
        
        # Get most likely class and its probability
        predicted_index = np.argmax(predictions[0])
        predicted_probability = float(predictions[0][predicted_index])
        predicted_label = CLASS_NAMES[predicted_index]
        predicted_description = CLASS_DESCRIPTIONS[predicted_label]
        
        result = {
            'label': predicted_label,
            'description': predicted_description,
            'probability': predicted_probability,
            'all_probabilities': {CLASS_NAMES[i]: float(predictions[0][i]) for i in range(len(CLASS_NAMES))}
        }
        
        logger.info(f"Prediction: {predicted_label} ({predicted_probability:.4f})")
        return result
        
    except Exception as e:
        logger.error(f"Error during prediction: {str(e)}")
        logger.error(traceback.format_exc())
        return None

def main():
    """Main function with improved error handling"""
    try:
        # Check arguments
        if len(sys.argv) < 2:
            logger.error("No image path provided.")
            print("Usage: python predict.py <image_path>")
            return 1

        image_path = sys.argv[1]
        logger.info(f"Starting prediction for image: {image_path}")
        
        # Load model
        model = load_model(MODEL_PATH)
        if model is None:
            print("Error: Could not load the model. Check prediction.log for details.")
            return 1
        
        # Preprocess image
        img_array = preprocess_image(image_path)
        if img_array is None:
            print("Error: Could not process the image. Check prediction.log for details.")
            return 1
        
        # Make prediction
        result = predict(model, img_array)
        if result is None:
            print("Error: Prediction failed. Check prediction.log for details.")
            return 1
        
        # Print results
        print("\n=== PREDICTION RESULTS ===")
        print(f"Diagnosis: {result['label']} - {result['description']}")
        print(f"Confidence: {result['probability']:.2%}")
        print("\nProbabilities for all classes:")
        print("image_path: ", image_path)
        
        # Sort by probability (highest first)
        sorted_probs = sorted(result['all_probabilities'].items(), key=lambda x: x[1], reverse=True)
        for label, prob in sorted_probs:
            description = CLASS_DESCRIPTIONS[label]
            print(f"- {label} ({description}): {prob:.2%}")
        
        return 0
        
    except Exception as e:
        logger.error(f"Unhandled exception: {str(e)}")
        logger.error(traceback.format_exc())
        print(f"Error: An unexpected error occurred. Check prediction.log for details.")
        return 1

if __name__ == '__main__':
    sys.exit(main())