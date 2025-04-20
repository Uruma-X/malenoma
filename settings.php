<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php include('navbar.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Fancy Settings Panel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="settings.css">
</head>
<body class="bg-gradient-to-br from-gray-900 to-indigo-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
<video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-[-10] brightness-[.2] saturate-[1.5] blur-sm">
  <source src="Bg.mp4" type="video/mp4" />
  Your browser does not support the video tag.
</video>

  <!-- Animated Futuristic Vehicles -->

  <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
    <!-- Flying car -->
    <img src="car.png" class="absolute animate-flycar w-32 h-24 bg-gradient-to-r from-blur-500 to-fuchsia-500 rounded-full shadow-md left-[-150px] top-40 opacity-60" ></div>
    <!-- Hover bike -->
    <img src="motor.png" class="absolute animate-bike w-40 h-24 bg-gradient-to-r from-blur-500 to-fuchsia-500 rounded-full shadow-md left-[-150px] top-40 opacity-60"></div>
    <!-- Spaceship -->
    <div class="absolute animate-ship w-24 h-12 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full shadow-lg left-[-250px] top-1/2 opacity-70"></div>
  </div>

  <div class="w-full max-w-4xl p-10 rounded-3xl shadow-2xl bg-white/5 backdrop-blur-xl border border-white/10 text-white z-10">
    <h1 class="text-4xl font-bold text-center mb-8 text-cyan-400">User Settings</h1>

    <div class="grid md:grid-cols-2 gap-10">
      <!-- Profile Picture -->
      <div class="flex flex-col items-center">
        <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-cyan-400 shadow-md mb-4">
          <img src="/path/to/profile.jpg" alt="Profile Picture" class="object-cover w-full h-full" id="profilePreview">
        </div>
        <input type="file" id="profilePic" name="profilePic" accept="image/*" class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-500 file:text-white hover:file:bg-cyan-600 cursor-pointer">
      </div>

      <form action="update_settings.php" method="POST" enctype="multipart/form-data" class="space-y-6">
        <!-- Username -->
        <div>
          <label for="username" class="block mb-1 text-sm font-semibold text-cyan-300">Username</label>
          <input type="text" id="username" name="username" placeholder="New username" class="w-full px-4 py-2 rounded-xl bg-white/10 text-white border border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-400">
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block mb-1 text-sm font-semibold text-cyan-300">Email</label>
          <input type="email" id="email" name="email" placeholder="New email" class="w-full px-4 py-2 rounded-xl bg-white/10 text-white border border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-400">
        </div>

        <!-- Password -->
        <div>
          <label for="password" class="block mb-1 text-sm font-semibold text-cyan-300">New Password</label>
          <input type="password" id="password" name="password" placeholder="New password" class="w-full px-4 py-2 rounded-xl bg-white/10 text-white border border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-400">
        </div>

        <!-- Confirm Password -->
        <div>
          <label for="confirm_password" class="block mb-1 text-sm font-semibold text-cyan-300">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" class="w-full px-4 py-2 rounded-xl bg-white/10 text-white border border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-400">
        </div>

        <div class="text-center">
          <button type="submit" class="px-6 py-2 bg-cyan-500 text-white rounded-xl hover:bg-cyan-600 transition shadow-lg">Save Changes</button>
        </div>
      </form>
    </div>

    <div class="mt-10 text-center text-sm text-gray-400">
      <p>Designed with ❤ using Tailwind CSS</p>
    </div>
  </div>

  <script>
    // Preview profile image
    document.getElementById('profilePic').addEventListener('change', function (e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
          document.getElementById('profilePreview').src = event.target.result;
        };
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>