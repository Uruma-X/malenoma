<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include('navbar.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .profile-border {
            position: relative;
        }
        .profile-border::before {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            background: linear-gradient(45deg, #00ccff, #0033ff, #ff00cc, #ff6600);
            z-index: -1;
            animation: rotate 6s linear infinite;
        }
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .star {
            position: absolute;
            background: white;
            border-radius: 50%;
            animation: twinkle 4s infinite;
        }
        @keyframes twinkle {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.8; }
        }
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: rgba(37, 99, 235, 0.2);
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, #3b82f6, #6366f1);
        }
        .timeline-line {
            position: absolute;
            left: 25px;
            top: 50px;
            bottom: 20px;
            width: 2px;
            background: linear-gradient(to bottom, #3b82f6, #8b5cf6);
        }
        .timeline-dot {
            position: absolute;
            left: 21px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #3b82f6;
            border: 2px solid #1e3a8a;
        }
        .scroll-indicator {
            animation: scrollDown 2s ease-in-out infinite;
        }
        @keyframes scrollDown {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(10px); }
        }
    </style>
</head>
<body class="bg-[url('Bg1.jpg')] bg-cover bg-center bg-no-repeat h-screen">
    
    <!-- Dynamic background stars -->
    <div id="stars-container" class="fixed inset-0 z-0"></div>
    
    <!-- Floating shapes -->
    <div class="fixed top-20 left-10 w-32 h-32 bg-gradient-to-r from-pink-500 to-purple-500 rounded-full opacity-20 animate-float" style="animation-delay: 0s;"></div>
    <div class="fixed bottom-20 right-10 w-40 h-40 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full opacity-20 animate-float" style="animation-delay: 2s;"></div>
    <div class="fixed top-40 right-20 w-24 h-24 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full opacity-20 animate-float" style="animation-delay: 1s;"></div>
    <div class="fixed bottom-40 left-20 w-36 h-36 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full opacity-20 animate-float" style="animation-delay: 3s;"></div>
    
    <!-- Header Section -->
    <section class="relative pt-24 pb-16 flex flex-col items-center justify-center min-h-screen">
        <div class="container mx-auto px-4 flex flex-col lg:flex-row gap-10 items-center relative z-10">
            <!-- Left Side: Profile Card -->
            <div class="w-full lg:w-1/3 flex flex-col items-center">
                <div class="bg-black bg-opacity-40 glass-effect p-10 rounded-3xl shadow-2xl border border-blue-500/30 w-full max-w-md">
                    <div class="flex justify-center mb-8">
                        <div class="profile-border">
                            <img src="profile-pic.jpg" alt="Profile Picture" class="w-48 h-48 rounded-full object-cover border-4 border-indigo-500">
                        </div>
                    </div>
                    
                    <h2 class="text-4xl font-bold text-center bg-gradient-to-r from-blue-400 to-purple-400 text-transparent bg-clip-text mb-1">John Doe</h2>
                    <p class="text-center text-blue-200 mb-2 flex items-center justify-center gap-2">
                        <i class="fas fa-envelope text-blue-300"></i>
                        johndoe@example.com
                    </p>
                    
                    <div class="bg-indigo-900/40 rounded-xl p-5 mt-6 border border-indigo-500/30">
                        <h3 class="text-xl font-semibold text-blue-300 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-2"></i>About Me
                        </h3>
                        <p class="text-gray-300 text-center italic">
                            "Photography enthusiast and gaming aficionado with an insatiable appetite for pizza and digital connections."
                        </p>
                    </div>
                    
                    <div class="mt-6 flex justify-center">
                        <button class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-6 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition duration-300 flex items-center gap-2 shadow-lg">
                            <i class="fas fa-camera-retro"></i>
                            Change Avatar
                        </button>
                    </div>
                </div>
                
                <!-- Social Media Links -->
                <div class="flex gap-4 mt-6">
                    <a href="#" class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-all transform hover:scale-110">
                        <i class="fab fa-twitter text-2xl"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700 transition-all transform hover:scale-110">
                        <i class="fab fa-instagram text-2xl"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-blue-800 rounded-full flex items-center justify-center hover:bg-blue-900 transition-all transform hover:scale-110">
                        <i class="fab fa-linkedin-in text-2xl"></i>
                    </a>
                    <a href="#" class="w-12 h-12 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-800 transition-all transform hover:scale-110">
                        <i class="fab fa-github text-2xl"></i>
                    </a>
                </div>
            </div>
            
            <!-- Right Side: Profile Details & Stats -->
            <div class="w-full lg:w-1/2">
                <!-- Personal Information -->
                <div class="bg-black bg-opacity-40 glass-effect p-8 rounded-3xl shadow-2xl border border-blue-500/30 mb-8">
                    <h3 class="text-2xl font-bold flex items-center gap-2 mb-6 text-blue-400">
                        <i class="fas fa-info-circle"></i>Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-indigo-900/40 p-4 rounded-xl border border-indigo-500/30 transition-all hover:bg-indigo-800/40">
                            <h4 class="text-blue-300 text-lg font-medium mb-2 flex items-center gap-2">
                                <i class="fas fa-camera text-indigo-400"></i>
                                Hobby
                            </h4>
                            <p class="text-lg">Photography</p>
                        </div>
                        
                        <div class="bg-indigo-900/40 p-4 rounded-xl border border-indigo-500/30 transition-all hover:bg-indigo-800/40">
                            <h4 class="text-blue-300 text-lg font-medium mb-2 flex items-center gap-2">
                                <i class="fas fa-pizza-slice text-indigo-400"></i>
                                Favorite Food
                            </h4>
                            <p class="text-lg">Pizza</p>
                        </div>
                        
                        <div class="bg-indigo-900/40 p-4 rounded-xl border border-indigo-500/30 transition-all hover:bg-indigo-800/40">
                            <h4 class="text-blue-300 text-lg font-medium mb-2 flex items-center gap-2">
                                <i class="fas fa-gamepad text-indigo-400"></i>
                                Favorite Game
                            </h4>
                            <p class="text-lg">The Legend of Zelda</p>
                        </div>
                        
                        <div class="bg-indigo-900/40 p-4 rounded-xl border border-indigo-500/30 transition-all hover:bg-indigo-800/40">
                            <h4 class="text-blue-300 text-lg font-medium mb-2 flex items-center gap-2">
                                <i class="fas fa-hashtag text-indigo-400"></i>
                                Favorite Social Media
                            </h4>
                            <p class="text-lg">Twitter</p>
                        </div>
                    </div>
                </div>
                
                <!-- Gallery Preview -->
                <div class="bg-black bg-opacity-40 glass-effect p-8 rounded-3xl shadow-2xl border border-blue-500/30">
                    <h3 class="text-2xl font-bold flex items-center gap-2 mb-6 text-blue-400">
                        <i class="fas fa-images"></i>Photo Gallery
                    </h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                            <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                        </div>
                        <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                            <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                        </div>
                        <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                            <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="#gallery" class="text-blue-400 hover:text-blue-300 inline-flex items-center">
                            View all photos
                            <i class="fas fa-chevron-down ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 text-center text-blue-300 scroll-indicator">
            <p class="mb-2">Scroll Down</p>
            <i class="fas fa-chevron-down text-2xl"></i>
        </div>
    </section>
    
    <!-- Skills Section -->
    <section class="bg-[url('Bg2.jpg')] bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-16 bg-gradient-to-r from-blue-400 to-purple-400 text-transparent bg-clip-text">My Skills</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto">
                <div class="bg-black bg-opacity-40 glass-effect p-8 rounded-3xl shadow-2xl border border-blue-500/30">
                    <h3 class="text-2xl font-semibold text-blue-400 mb-8">Technical Skills</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-blue-300">Photography</span>
                                <span class="text-blue-300">90%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 90%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-blue-300">HTML/CSS</span>
                                <span class="text-blue-300">85%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 85%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-blue-300">JavaScript</span>
                                <span class="text-blue-300">75%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 75%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-blue-300">PHP</span>
                                <span class="text-blue-300">80%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 80%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-blue-300">Photoshop</span>
                                <span class="text-blue-300">95%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 95%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-black bg-opacity-40 glass-effect p-8 rounded-3xl shadow-2xl border border-blue-500/30">
                    <h3 class="text-2xl font-semibold text-blue-400 mb-8">Soft Skills</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-blue-300">Communication</span>
                                <span class="text-blue-300">85%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 85%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-blue-300">Teamwork</span>
                                <span class="text-blue-300">90%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 90%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-blue-300">Problem Solving</span>
                                <span class="text-blue-300">95%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 95%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-blue-300">Creativity</span>
                                <span class="text-blue-300">88%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 88%"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-blue-300">Time Management</span>
                                <span class="text-blue-300">83%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 83%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Experience Timeline Section -->
    <section class="bg-[url('Bg3.jpg')] bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-16 bg-gradient-to-r from-blue-400 to-purple-400 text-transparent bg-clip-text">Experience</h2>
            
            <div class="max-w-4xl mx-auto bg-black bg-opacity-40 glass-effect p-8 rounded-3xl shadow-2xl border border-blue-500/30 relative">
                <!-- Timeline line -->
                <div class="timeline-line"></div>
                
                <!-- Timeline items -->
                <div class="space-y-14">
                    <div class="relative pl-16">
                        <div class="timeline-dot" style="top: 4px;"></div>
                        <div class="mb-2">
                            <h3 class="text-xl font-semibold text-blue-400">Senior Web Developer</h3>
                            <p class="text-blue-300">XYZ Company • 2023 - Present</p>
                        </div>
                        <p class="text-gray-300">Led a team of developers in creating responsive web applications for various clients. Specialized in frontend development with React and backend integration with PHP.</p>
                    </div>
                    
                    <div class="relative pl-16">
                        <div class="timeline-dot" style="top: 4px;"></div>
                        <div class="mb-2">
                            <h3 class="text-xl font-semibold text-blue-400">Web Developer</h3>
                            <p class="text-blue-300">ABC Studio • 2020 - 2023</p>
                        </div>
                        <p class="text-gray-300">Developed and maintained websites for multiple clients. Implemented responsive designs and ensured cross-browser compatibility.</p>
                    </div>
                    
                    <div class="relative pl-16">
                        <div class="timeline-dot" style="top: 4px;"></div>
                        <div class="mb-2">
                            <h3 class="text-xl font-semibold text-blue-400">Junior Developer</h3>
                            <p class="text-blue-300">Tech Solutions Inc • 2018 - 2020</p>
                        </div>
                        <p class="text-gray-300">Assisted senior developers in building and testing web applications. Gained experience in HTML, CSS, JavaScript, and PHP.</p>
                    </div>
                    
                    <div class="relative pl-16">
                        <div class="timeline-dot" style="top: 4px;"></div>
                        <div class="mb-2">
                            <h3 class="text-xl font-semibold text-blue-400">Intern</h3>
                            <p class="text-blue-300">Digital Media Agency • 2017 - 2018</p>
                        </div>
                        <p class="text-gray-300">Worked on various projects focusing on UI/UX design and front-end development. Created mockups and implemented designs using HTML and CSS.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Full Gallery Section -->
    <section class="bg-[url('Bg4.jpg')] bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-16 bg-gradient-to-r from-blue-400 to-purple-400 text-transparent bg-clip-text">Photo Gallery</h2>
            
            <div class="max-w-6xl mx-auto bg-black bg-opacity-40 glass-effect p-8 rounded-3xl shadow-2xl border border-blue-500/30">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                    <div class="aspect-square overflow-hidden rounded-xl border-2 border-indigo-500/50 hover:border-indigo-400 transition-all hover:scale-105">
                        <img src="/api/placeholder/300/300" alt="Gallery Image" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="bg-[url('Bg5.jpg')] bg-cover bg-center bg-no-repeat min-h-screen flex items-center justify-center">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-16 bg-gradient-to-r from-blue-400 to-purple-400 text-transparent bg-clip-text">Contact Me</h2>
            
            <div class="max-w-4xl mx-auto bg-black bg-opacity-40 glass-effect p-8 rounded-3xl shadow-2xl border border-blue-500/30">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-2xl font-semibold text-blue-400 mb-6">Get In Touch</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center mr-4">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <p class="text-blue-300 text-sm">Email</p>
                                    <p>johndoe@example.com</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center mr-4">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-blue-300 text-sm">Phone</p>
                                    <p>+1 (555) 123-4567</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center mr-4">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <p class="text-blue-300 text-sm">Location</p>
                                    <p>San Francisco, CA</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-8">
                            <h4 class="text-xl font-semibold text-blue-400 mb-4">Follow Me</h4>
                            <div class="flex gap-4">
                                <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 transition-all">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center hover:bg-pink-700 transition-all">
                                    <i class="fab fa-instagram"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-blue-800 rounded-full flex items-center justify-center hover:bg-blue-900 transition-all">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-gray-800 transition-all">
                                    <i class="fab fa-github"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-2xl font-semibold text-blue-400 mb-6">Send Message</h3>
                        
                        <form>
                            <div class="mb-4">
                                <input type="text" placeholder="Your Name" class="w-full p-3 rounded-lg bg-indigo-900/40 border border-indigo-500/30 text-white placeholder-blue-300/50 focus:outline-none focus:border-blue-400">
                            </div>
                            
                            <div class="mb-4">
                                <input type="email" placeholder="
