# server.py (versi multi-class)
from flask import Flask, request, jsonify
from PIL import Image
import numpy as np
import tensorflow as tf
import os
import logging
from flask_cors import CORS

# Loggingpy
logging.basicConfig(level=logging.DEBUG)
logger = logging.getLogger(__name__)

app = Flask(__name__)
CORS(app)

UPLOAD_FOLDER = 'uploads'
if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

# === Kelas HAM10000 ===# server.py
from flask import Flask, request, jsonify
from PIL import Image
import numpy as np
import tensorflow as tf
import os
import logging
from flask_cors import CORS
from werkzeug.utils import secure_filename

logging.basicConfig(level=logging.DEBUG)
logger = logging.getLogger(__name__)

app = Flask(__name__)
CORS(app)

UPLOAD_FOLDER = 'uploads'
os.makedirs(UPLOAD_FOLDER, exist_ok=True)

CLASS_NAMES = ['akiec', 'bcc', 'bkl', 'df', 'mel', 'nv', 'vasc']

MODEL_PATH = os.path.join(UPLOAD_FOLDER, 'melanoma_model.h5')
try:
    logger.info(f"Loading model from {MODEL_PATH}")
    model = tf.keras.models.load_model(MODEL_PATH)
    logger.info("Model loaded successfully")
except Exception as e:
    logger.error(f"Error loading model: {str(e)}")
    model = None

def prepare_image(image_path):
    try:
        img = Image.open(image_path).convert('RGB').resize((224, 224))
        return np.array(img) / 255.0
    except Exception as e:
        logger.error(f"Error preparing image: {str(e)}")
        raise

@app.route('/')
def home():
    return jsonify({'status': 'API is running'})

@app.route('/predict', methods=['POST'])
def predict():
    if model is None:
        return jsonify({'error': 'Model not loaded'}), 500

    if 'image' not in request.files:
        return jsonify({'error': 'No image file provided'}), 400

    file = request.files['image']
    if file.filename == '':
        return jsonify({'error': 'Empty filename'}), 400

    try:
        filename = secure_filename(file.filename)
        file_path = os.path.join(UPLOAD_FOLDER, filename)
        file.save(file_path)

        img = prepare_image(file_path)
        img = np.expand_dims(img, axis=0)
        prediction = model.predict(img)[0]

        top_index = int(np.argmax(prediction))
        label = CLASS_NAMES[top_index]
        confidence = float(prediction[top_index])

        logger.info(f"Prediction result: {label} ({confidence:.4f})")

        return jsonify({
            'prediction': label,
            'confidence': confidence
        })

    except Exception as e:
        logger.error(f"Prediction error: {str(e)}")
        return jsonify({'error': f'Prediction error: {str(e)}'}), 500

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)

CLASS_NAMES = ['akiec', 'bcc', 'bkl', 'df', 'mel', 'nv', 'vasc']


import wget

MODEL_PATH = os.path.join(UPLOAD_FOLDER, 'melanoma_model.h5')
MODEL_URL = 'https://drive.google.com/uc?export=download&id=1CexnoG6hB_9Ia0v_WhN4vy8Q0hVgb9LJ'

if not os.path.exists(MODEL_PATH):
    logger.info("Model not found. Downloading from Google Drive...")
    wget.download(MODEL_URL, MODEL_PATH)
    logger.info("Download completed.")



# Load model
MODEL_PATH = os.path.join(UPLOAD_FOLDER, 'melanoma_model.h5')
try:
    logger.info(f"Loading model from {MODEL_PATH}")
    model = tf.keras.models.load_model(MODEL_PATH)
    logger.info("Model loaded successfully")
except Exception as e:
    logger.error(f"Error loading model: {str(e)}")
    model = None

def prepare_image(image_path):
    try:
        img = Image.open(image_path).convert('RGB').resize((224, 224))
        return np.array(img) / 255.0
    except Exception as e:
        logger.error(f"Error preparing image: {str(e)}")
        raise

@app.route('/')
def home():
    return jsonify({'status': 'API is running'})

@app.route('/predict', methods=['POST'])
def predict():
    if model is None:
        return jsonify({'error': 'Model not loaded'}), 500

    if 'image' not in request.files:
        return jsonify({'error': 'No image file provided'}), 400

    file = request.files['image']
    if file.filename == '':
        return jsonify({'error': 'Empty filename'}), 400

    try:
        # Simpan file
        file_path = os.path.join(UPLOAD_FOLDER, file.filename)
        file.save(file_path)

        # Prediksi
        img = prepare_image(file_path)
        img = np.expand_dims(img, axis=0)
        prediction = model.predict(img)[0]

        top_index = np.argmax(prediction)
        label = CLASS_NAMES[top_index]
        confidence = float(prediction[top_index])

        return jsonify({
            'prediction': label,
            'confidence': confidence
        })

    except Exception as e:
        logger.error(f"Prediction error: {str(e)}")
        return jsonify({'error': f'Prediction error: {str(e)}'}), 500

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)
