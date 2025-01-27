<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AccueilAdmin - gestImmo</title>
    <link rel="stylesheet" href="assets/css/styleAccueil.css">
    <style>
        /* General styles */
        body {
            font-family: 'Arial', sans-serif;
            max-width: 100%;
            margin: 0 auto;
            padding: 45px;
            background: linear-gradient(to right, #007bff, #dff3f8);
            line-height: 1.6;
        }
        nav {
            background-color: #1f2937;
            color: #fff;
            width: 41%;
            position: absolute;
            align-content: center;
            left: 30%;
        }

        .container {
            max-width: 1202px;
            margin: 0 auto;
            padding: 0 8px;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 67px;
        }

        .logo img {
            height: 32px;
        }

        .nav-links {
            display: flex;
            gap: 58px;
        }

        .nav-links a {
            color: #d1d5db; /* Gray-300 */
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
        }

        .nav-links a:hover {
            background-color: #374151; /* Gray-700 */
            color: #fff;
        }

        .nav-links .active {
            background-color: #111827; /* Gray-900 */
            color: #fff;
        }

        .profile-menu {
            position: relative;
        }

        .profile-menu img {
            height: 45px;
            width: 45px;
            border-radius: 50%;
            cursor: pointer;
        }

        .dropdown {
            display: none;
            position: absolute;
            right: 0;
            background-color: #fff;
            color: #000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            overflow: hidden;
            z-index: 10;
        }

        .dropdown a {
            display: block;
            padding: 8px 12px;
            color: #374151; /* Gray-700 */
            text-decoration: none;
            font-size: 14px;
        }

        .dropdown a:hover {
            background-color: #f3f4f6; /* Gray-100 */
        }

        .mobile-menu-button {
            display: none;
            background: none;
            border: none;
            color: #d1d5db;
            cursor: pointer;
            font-size: 24px;
        }

        .mobile-menu {
            display: none;
            flex-direction: column;
            gap: 8px;
            padding: 8px 16px;
        }

        .mobile-menu a {
            text-align: left;
        }

        @media (max-width: 768px) {
            .nav-links {
            display: none;
            }

            .mobile-menu-button {
            display: block;
            }

            .mobile-menu {
            display: flex;
            }
        }
            
  </style>
</head> 
<body>
    <h1>Bienvenue sur gestImmo</h1>
    
    <nav>
    <div class="container">
      <div class="navbar">
        <div class="logo">
          <img src="images/images.jpeg" alt="Your Company">
        </div>
        <div class="nav-links">
          <!-- <a href="#" class="active">Dashboard</a> -->
          <a href="appartements.php">Appartements</a>
        <a href="immeubles.php">Immeubles</a>
        <a href="listeClients.php">Liste des clients</a>
        </div>
        <button class="mobile-menu-button" onclick="toggleMobileMenu()">☰</button>
        <div class="profile-menu">
          <img src="images/6796ac204fde2-image.jpg" alt="User" onclick="toggleDropdown()">
         
        </div>
      </div>
      <div class="mobile-menu">
        <!-- <a href="#" class="active">Dashboard</a> -->
        <a href="appartements.php">Appartements</a>
        <a href="immeubles.php">Immeubles</a>
        <a href="listeClients.php">Liste des clients</a>
      </div>
    </div>
  </nav>
</body>
<script>
    const dropdown = document.querySelector('.dropdown');
    const mobileMenu = document.querySelector('.mobile-menu');
    let isDropdownOpen = false;
    let isMobileMenuOpen = false;

    function toggleDropdown() {
      isDropdownOpen = !isDropdownOpen;
      dropdown.style.display = isDropdownOpen ? 'block' : 'none';
    }

    function toggleMobileMenu() {
      isMobileMenuOpen = !isMobileMenuOpen;
      mobileMenu.style.display = isMobileMenuOpen ? 'flex' : 'none';
    }

    document.addEventListener('click', (e) => {
      const profileMenu = document.querySelector('.profile-menu img');
      if (e.target !== profileMenu && !profileMenu.contains(e.target)) {
        dropdown.style.display = 'none';
        isDropdownOpen = false;
      }
    });
  </script>
</body>
</html>
