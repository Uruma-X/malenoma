<?php
session_start();

// Cek apakah user sudah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION["username"])) {
    header("Location: dashboard.php"); // Ganti dengan halaman login Anda
    exit();
}

// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$db = "data_db";

$conn = mysqli_connect($host, $user, $password, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ambil data user yang sedang login
$username = $_SESSION["username"];
$query = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Tentukan konten yang akan dimuat
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$contentFile = '';

switch ($page) {
    case 'penyakit':
        $contentFile = 'Penyakit.php';
        break;
    case 'gejala':
        $contentFile = 'Gejala.php';
        break;
    case 'cara_mencegah':
        $contentFile = 'CaraMencegah.php';
        break;
    case 'settings':
        $contentFile = 'settings.php';
        break;
    case 'profile':
        $contentFile = 'profile.php';
        break;   
    default:
        $contentFile = 'home.php'; // Halaman default
        break;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  </head>
  <body class= "bg-[url('Jpg1.jpg')] bg-cover bg-center h-screen">
    <script src="dark-mode.js"></script>
   <!-- dashboard.php -->
<nav class="fixed z-30 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
  <form id="uploadForm" action="uploads/upload_multiple.php" method="POST" enctype="multipart/form-data">
    <div class="fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-50 pointer-events-none">
      <div class="bg-gray-800 text-white rounded-xl shadow-xl p-8 w-full max-w-md text-center pointer-events-auto">
        <h2 class="text-2xl font-semibold mb-4">Upload Your Image</h2>

        <!-- Upload area -->
        <label for="image-upload" class="block border-2 border-dashed border-gray-500 rounded-lg p-20 mb-4 cursor-pointer hover:border-green-400 transition">
          <p class="mb-1">Click or drag image to upload</p>
          <small class="text-gray-400">PNG, JPG, JPEG (Max 5MB)</small>
        </label>
        <input id="image-upload" type="file" name="images[]" accept="image/png, image/jpeg" class="hidden" multiple />

        <!-- Preview -->
        <div id="previewContainer" class="grid grid-cols-3 gap-2 mb-4"></div>
        <div id="fileCounter" class="text-gray-300 mb-4">0 files selected</div>

        <!-- Submit -->
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-6 rounded-lg transition">
          Upload Image
        </button>

        <!-- Results -->
        <div id="resultContainer" class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4"></div>
      </div>
    </div>
  </form>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const uploadForm = document.getElementById('uploadForm');
  const imageInput = document.getElementById('image-upload');
  const uploadLabel = document.querySelector('label[for="image-upload"]');
  const uploadButton = document.querySelector('button[type="submit"]');
  const previewContainer = document.getElementById('previewContainer');
  const fileCounter = document.getElementById('fileCounter');
  const resultContainer = document.getElementById('resultContainer');

  let selectedFiles = [];

  imageInput.setAttribute('multiple', 'true');

  uploadLabel.addEventListener('dragover', e => {
    e.preventDefault();
    uploadLabel.classList.add('border-green-400');
  });

  uploadLabel.addEventListener('dragleave', () => {
    uploadLabel.classList.remove('border-green-400');
  });

  uploadLabel.addEventListener('drop', e => {
    e.preventDefault();
    uploadLabel.classList.remove('border-green-400');
    const newFiles = Array.from(e.dataTransfer.files);
    addToQueue(newFiles);
  });

  imageInput.addEventListener('change', e => {
    const newFiles = Array.from(e.target.files);
    addToQueue(newFiles);
  });

  uploadForm.addEventListener('submit', e => {
    e.preventDefault();
    if (selectedFiles.length === 0) {
      alert('Please select at least one image.');
      return;
    }

    const formData = new FormData();
    selectedFiles.forEach(file => {
      formData.append('images[]', file);
    });

    uploadButton.disabled = true;
    uploadButton.textContent = 'Uploading...';

    fetch(uploadForm.action, {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      uploadButton.disabled = false;
      uploadButton.textContent = 'Upload Image';
      clearQueue();
      resultContainer.innerHTML = '';

      if (data.success && Array.isArray(data.files)) {
        data.files.forEach(file => {
          const card = document.createElement('div');
          card.className = 'bg-white p-4 rounded shadow text-center';

          if (file.success) {
            card.innerHTML = `
              <img src="${file.url}" class="w-full h-40 object-cover rounded mb-2" />
              <p class="text-sm text-gray-500">Prediction</p>
              <span class="text-lg font-bold text-green-600">${file.prediction}</span>
            `;
          } else {
            card.innerHTML = `<p class="text-red-500">Failed: ${file.message}</p>`;
          }

          resultContainer.appendChild(card);
        });
      } else {
        resultContainer.innerHTML = `<p class="text-red-500">Upload gagal: ${data.message || 'Unknown error'}</p>`;
      }
    })
    .catch(err => {
      alert('Upload failed. Please try again.');
      uploadButton.disabled = false;
      uploadButton.textContent = 'Upload Image';
    });
  });
    

  function addToQueue(files) {
    files.forEach(file => {
      if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
        alert('Only JPG and PNG images are allowed.');
        return;
      }
      if (file.size > 5 * 1024 * 1024) {
        alert('Max file size is 5MB.');
        return;
      }

      selectedFiles.push(file);
      displayPreview(file);
    });
    updateCounter();
  }

  function displayPreview(file) {
    const preview = document.createElement('div');
    preview.className = 'relative group';

    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    img.className = 'w-full h-20 object-cover rounded';

    const removeBtn = document.createElement('button');
    removeBtn.innerHTML = '&times;';
    removeBtn.className = 'absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100';
    removeBtn.addEventListener('click', () => {
      removeFile(file, preview);
    });

    preview.appendChild(img);
    preview.appendChild(removeBtn);
    previewContainer.appendChild(preview);
  }

  function removeFile(file, element) {
    const index = selectedFiles.indexOf(file);
    if (index > -1) {
      selectedFiles.splice(index, 1);
      element.remove();
      updateCounter();
    }
  }

  function updateCounter() {
    const count = selectedFiles.length;
    fileCounter.textContent = `${count} file${count !== 1 ? 's' : ''} selected`;
    uploadButton.textContent = count > 0 ? 'Upload All Images' : 'Upload Image';
  }

  function clearQueue() {
    selectedFiles = [];
    previewContainer.innerHTML = '';
    updateCounter();
  }
});
</script>

</body>
</html>
      
  
  </div>
</div>
<nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
  <div class="px-3 py-3 lg:px-5 lg:pl-3">
    <div class="flex items-center justify-between">
      
      <!-- Logo dan tombol sidebar -->
      <div class="flex items-center">
        <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button"
          class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
          <span class="sr-only">Open sidebar</span>
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path clip-rule="evenodd" fill-rule="evenodd"
              d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
            </path>
          </svg>
        </button>
        <a href="/" class="flex ml-2 md:mr-24">
          <img src="Krab.jpg" class="h-8 mr-3" alt="Logo" />
          <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Kejoul Corporation</span>
        </a>
      </div>

      <!-- Navigasi Atas -->
      <div class="flex items-center space-x-2">

        <!-- Dashboard -->
        <a href="dashboard.php"
          class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg hover:scale-105 transition-transform duration-300 flex items-center space-x-2 shadow-lg backdrop-blur">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
          </svg>
          <span>Dashboard</span>
        </a>

        <!-- Dropdown: Informasi Umum -->
        <div class="relative group">
          <button
            class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg hover:scale-105 transition-transform duration-300 flex items-center space-x-2 shadow-lg backdrop-blur">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path
                d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z">
              </path>
            </svg>
            <span>Informasi Umum</span>
            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <div
            class="absolute left-0 z-10 mt-2 origin-top bg-white/10 backdrop-blur-md rounded-xl shadow-2xl opacity-0 scale-95 invisible group-hover:opacity-100 group-hover:scale-100 group-hover:visible transition-all duration-300 delay-100 px-4 py-2">
            <div class="space-y-2">
              <a href="Penyakit.php?page=penyakit"
                class="block px-2 py-1.5 text-sm font-semibold text-gray-400 rounded-md hover:bg-white/10">Penyakit</a>
              <a href="Gejala.php"
                class="block px-2 py-1.5 text-sm font-semibold text-gray-400 rounded-md hover:bg-white/10">Gejala</a>
              <a href="Cara Mencegah"
                class="block px-2 py-1.5 text-sm font-semibold text-gray-400 rounded-md hover:bg-white/10">Cara Mencegah</a>
            </div>
          </div>
        </div>

        <!-- Dropdown: Contact -->
        <div class="relative group">
          <button
            class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg hover:scale-105 transition-transform duration-300 flex items-center space-x-2 shadow-lg backdrop-blur">
            <img src="ctc5.png" alt="Contact Icon" class="w-5 h-5" />
            <span>Contact</span>
            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
            </svg>
          </button>
          <div
            class="absolute left-0 z-10 mt-2 origin-top bg-white/10 backdrop-blur-md rounded-xl shadow-2xl opacity-0 scale-95 invisible group-hover:opacity-100 group-hover:scale-100 group-hover:visible transition-all duration-300 delay-100 px-4 py-2">
            <div class="space-y-2">
              <a href="https://wa.me/6285728005019" target="_blank"
                class="flex items-center gap-2 text-sm font-semibold text-green-400 hover:bg-white/10 px-2 py-1.5 rounded-md">
                WhatsApp <img src="wa.png" class="h-5 w-5" />
              </a>
              <a href="https://instagram.com/zetadetio" target="_blank"
                class="flex items-center gap-2 text-sm font-semibold text-pink-400 hover:bg-white/10 px-2 py-1.5 rounded-md">
                Instagram <img src="ig.png" class="h-5 w-5" />
              </a>
              <a href="https://www.facebook.com/Bukan.esempe?locale=id_ID" target="_blank"
                class="flex items-center gap-2 text-sm font-semibold text-blue-400 hover:bg-white/10 px-2 py-1.5 rounded-md">
                Facebook <img src="fb1.png" class="h-5 w-5" />
              </a>
            </div>
          </div>
        </div>

        <!-- Settings -->
        <a href="settings.php"
          class="px-4 py-2 text-sm font-semibold text-white bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg hover:scale-105 transition-transform duration-300 flex items-center space-x-2 shadow-lg backdrop-blur">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
              clip-rule="evenodd" />
          </svg>
          <span>Settings</span>
        </a>

        <!-- Search -->
        <div class="mx-3 relative">
          <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <input type="text"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full pl-10 p-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
            placeholder="Search">
        </div>

        <!-- User Menu -->
        <div class="relative">
          <button type="button"
            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            id="user-menu-button">
            <span class="sr-only">Open user menu</span>
            <img class="w-8 h-8 rounded-full" src="https://api.dicebear.com/6.x/avataaars/svg?seed=user"
              alt="user photo">
          </button>
          <div class="hidden absolute right-0 z-50 mt-2 w-56 bg-white rounded shadow dark:bg-gray-700" id="user-dropdown">
            <div class="py-3 px-4">
              <span class="block text-sm font-medium text-gray-900 dark:text-white">
                <?php echo $_SESSION['username']; ?>
              </span>
              <span class="block text-sm text-gray-500 truncate dark:text-gray-400">
                <?php echo $_SESSION['email']; ?>
              </span>
            </div>
            <ul class="py-1">
              <li><a href="profile.php"
                  class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Profile</a></li>
              <li><a href="login.php"
                  class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Log
                  Out</a></li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</nav>
  
  <!-- Mobile menu, show/hide based on menu state -->
  <div class="hidden md:hidden" id="mobile-menu">
    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
      <a href="/dashboard" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Dashboard</a>
      
      <!-- Mobile Layouts Dropdown -->
      <div class="relative" id="mobile-layouts-dropdown">
        <button class="flex items-center justify-between w-full px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
          Layouts
          <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <div class="hidden pl-4 mt-1 space-y-1" id="mobile-layouts-items">
          <a href="/layouts/stacked" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Stacked</a>
          <a href="/layouts/sidebar" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Sidebar</a>
        </div>
      </div>
      
      <!-- Mobile CRUD Dropdown -->
      <div class="relative" id="mobile-crud-dropdown">
        <button class="flex items-center justify-between w-full px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
          CRUD
          <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <div class="hidden pl-4 mt-1 space-y-1" id="mobile-crud-items">
          <a href="/crud/products" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Products</a>
          <a href="/crud/users" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Users</a>
        </div>
      </div>
      
      <a href="/settings" class="block px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">Settings</a>
      
      <!-- Mobile Theme Toggle -->
      <button id="mobile-theme-toggle" class="flex items-center w-full px-3 py-2 text-base font-medium text-gray-700 rounded-md hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">
        <span id="mobile-theme-toggle-text">Change Theme</span>
      </button>
    </div>
  </div>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle mobile menu
      const mobileMenuButton = document.getElementById('mobile-menu-button');
      const mobileMenu = document.getElementById('mobile-menu');
      
      if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
          mobileMenu.classList.toggle('hidden');
        });
      }
      
      // Toggle user dropdown
      const userMenuButton = document.getElementById('user-menu-button');
      const userDropdown = document.getElementById('user-dropdown');
      
      if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', function() {
          userDropdown.classList.toggle('hidden');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
          if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
            userDropdown.classList.add('hidden');
          }
        });
      }
      
      // Toggle mobile dropdowns
      const mobileLayoutsDropdown = document.getElementById('mobile-layouts-dropdown');
      const mobileLayoutsItems = document.getElementById('mobile-layouts-items');
      const mobileCrudDropdown = document.getElementById('mobile-crud-dropdown');
      const mobileCrudItems = document.getElementById('mobile-crud-items');
      
      if (mobileLayoutsDropdown && mobileLayoutsItems) {
        mobileLayoutsDropdown.querySelector('button').addEventListener('click', function() {
          mobileLayoutsItems.classList.toggle('hidden');
        });
      }
      
      if (mobileCrudDropdown && mobileCrudItems) {
        mobileCrudDropdown.querySelector('button').addEventListener('click', function() {
          mobileCrudItems.classList.toggle('hidden');
        });
      }
      
      // Theme toggle functionality
      const themeToggleBtn = document.getElementById('theme-toggle');
      const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
      const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
      const themeToggleText = document.getElementById('theme-toggle-text');
      const mobileThemeToggleBtn = document.getElementById('mobile-theme-toggle');
      const mobileThemeToggleText = document.getElementById('mobile-theme-toggle-text');
      
      // Check for saved theme preference or use system preference
      if (localStorage.getItem('color-theme') === 'dark' || 
          (!localStorage.getItem('color-theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        themeToggleDarkIcon.classList.add('hidden');
        themeToggleLightIcon.classList.remove('hidden');
        themeToggleText.textContent = 'Light Mode';
        mobileThemeToggleText.textContent = 'Light Mode';
      } else {
        document.documentElement.classList.remove('dark');
        themeToggleDarkIcon.classList.remove('hidden');
        themeToggleLightIcon.classList.add('hidden');
        themeToggleText.textContent = 'Dark Mode';
        mobileThemeToggleText.textContent = 'Dark Mode';
      }
      
      // Toggle theme
      function toggleTheme() {
        if (document.documentElement.classList.contains('dark')) {
          document.documentElement.classList.remove('dark');
          localStorage.setItem('color-theme', 'light');
          themeToggleDarkIcon.classList.remove('hidden');
          themeToggleLightIcon.classList.add('hidden');
          themeToggleText.textContent = 'Dark Mode';
          mobileThemeToggleText.textContent = 'Dark Mode';
        } else {
          document.documentElement.classList.add('dark');
          localStorage.setItem('color-theme', 'dark');
          themeToggleDarkIcon.classList.add('hidden');
          themeToggleLightIcon.classList.remove('hidden');
          themeToggleText.textContent = 'Light Mode';
          mobileThemeToggleText.textContent = 'Light Mode';
        }
      }
      
      // Add event listeners for theme toggle
      if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', toggleTheme);
      }
      
      if (mobileThemeToggleBtn) {
        mobileThemeToggleBtn.addEventListener('click', toggleTheme);
      }
      
      // Highlight active menu item based on current path
      const currentPath = window.location.pathname;
      const navLinks = document.querySelectorAll('nav a');
      
      navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
          link.classList.add('bg-gray-100', 'dark:bg-gray-700');
          
          // If in dropdown, ensure parent shows active state
          const parentDropdown = link.closest('.group');
          if (parentDropdown) {
            const dropdownButton = parentDropdown.querySelector('button');
            if (dropdownButton) {
              dropdownButton.classList.add('bg-gray-100', 'dark:bg-gray-700');
            }
          }
        }
      });
    });
  </script>
</nav>
<div class="fixed inset-0 z-10 hidden bg-gray-900/50 dark:bg-gray-900/90" id="sidebarBackdrop"></div>





  </body>

</html>

<?php
mysqli_close($conn);
?>


