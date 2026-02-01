<?php
require_once __DIR__ . '/init.php';
$auth = new Auth();
$contactModel = new Contact();
$productModel = new Product();

$homePage = $contactModel->getPageBySlug('home');
$sliderItems = $contactModel->getSliderItems();
$products = $productModel->getAll(9);

$pageTitle = 'Home';
$heroSubtitle = $homePage['title'] ?? 'Helping Others';
$heroTitle = 'LIVE & TRAVEL';
$heroDesc = $homePage['content'] ?? 'Special offers to suit your plan.';
if (!empty($sliderItems)) {
    $first = $sliderItems[0];
    $heroSubtitle = $first['title'] ?? $heroSubtitle;
    $heroTitle = $first['subtitle'] ?? $heroTitle;
}

require __DIR__ . '/includes/header.php';
?>
      <div class="form-container">
        <div><h2 style="font-size: 1em; display: flex; align-items: center;"><img src="assets/Images/airplanee.png" alt="icon" style="padding-right: 6px;"> Flights</h2></div>
        <form action="products.php" method="get">
          <div class="form-group">
            <div>
              <label for="from">From</label>
              <select id="from" name="from">
                <option value="Tirana">Tirana</option>
                <option value="Skopje">Skopje</option>
                <option value="Preshevë">Preshevë</option>
                <option value="Prishtinë">Prishtinë</option>
                <option value="Istanbul">Istanbul</option>
                <option value="London">London</option>
              </select>
            </div>
            <div>
              <label for="to">To</label>
              <select id="to" name="to">
                <option value="Milan">Milan</option>
                <option value="Roma">Roma</option>
                <option value="Dubai">Dubai</option>
                <option value="Doha">Doha</option>
                <option value="Prishtinë">Prishtinë</option>
                <option value="Preshevë">Preshevë</option>
                <option value="Istanbul">Istanbul</option>
                <option value="London">London</option>
              </select>
            </div>
            <div>
              <label for="trip">Trip</label>
              <select id="trip" name="trip">
                <option value="one-way">One-way</option>
                <option value="return">Return</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <div>
              <label for="depart">Depart</label>
              <input type="date" id="depart" name="depart">
            </div>
            <div>
              <label for="return">Return</label>
              <input type="date" id="return" name="return">
            </div>
            <div>
              <label for="passenger-class">Passenger - Class</label>
              <select id="passenger-class" name="passenger-class">
                <option value="1-business">1 Passenger, Business</option>
                <option value="1-economy">1 Passenger, Economy</option>
                <option value="2-business">2 Passengers, Business</option>
                <option value="2-economy">2 Passengers, Economy</option>
              </select>
            </div>
          </div>
          <button type="submit" class="submit-btn">Show Flights</button>
        </form>
      </div>

      <?php if (count($sliderItems) > 1): ?>
      <div class="slider-container" style="max-width: 1200px; margin: 2rem auto; overflow: hidden; position: relative;">
        <div class="hero-slider" style="display: flex; transition: transform 0.5s;">
          <?php foreach ($sliderItems as $slide): ?>
            <div class="slide" style="min-width: 100%; text-align: center; padding: 2rem;">
              <?php if (!empty($slide['image_path'])): ?>
                <img src="<?= htmlspecialchars($slide['image_path']) ?>" alt="<?= htmlspecialchars($slide['title'] ?? '') ?>" style="max-width: 100%; max-height: 300px; object-fit: cover;">
              <?php endif; ?>
              <h3><?= htmlspecialchars($slide['title'] ?? '') ?></h3>
              <p><?= htmlspecialchars($slide['subtitle'] ?? '') ?></p>
            </div>
          <?php endforeach; ?>
        </div>
        <button type="button" class="slider-prev" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">‹</button>
        <button type="button" class="slider-next" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">›</button>
      </div>
      <script>
      (function(){
        var s = document.querySelector('.hero-slider');
        var slides = document.querySelectorAll('.slide');
        var i = 0;
        function go(n) { i = (i + n + slides.length) % slides.length; s.style.transform = 'translateX(-' + (i * 100) + '%)'; }
        document.querySelector('.slider-next')?.addEventListener('click', function(){ go(1); });
        document.querySelector('.slider-prev')?.addEventListener('click', function(){ go(-1); });
        setInterval(function(){ go(1); }, 5000);
      })();
      </script>
      <?php endif; ?>

      <div class="offerts">
        <div class="tit">
          <div class="features">
            <h1>Plan your perfect trip</h1>
            <p>Search Flights & Places – our most popular destinations</p>
          </div>
          <div>
            <a href="products.php" class="submit-btn">Show More</a>
          </div>
        </div>
        <div class="cards">
          <?php if (!empty($products)): ?>
            <?php foreach (array_slice($products, 0, 9) as $p): ?>
              <div class="card">
                <?php if (!empty($p['image_path'])): ?>
                  <img src="<?= htmlspecialchars($p['image_path']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" class="card-image">
                <?php else: ?>
                  <img src="assets/Images/Frame 197-1.png" alt="Card Image" class="card-image">
                <?php endif; ?>
                <div class="card-content">
                  <h3 class="card-title"><?= htmlspecialchars($p['title']) ?></h3>
                  <p class="card-text"><?= htmlspecialchars($p['from_location'] . ' → ' . $p['to_location']) ?></p>
                  <?php if (!empty($p['price'])): ?><p class="card-text">€<?= number_format($p['price'], 2) ?></p><?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="card">
              <img src="assets/Images/Frame 197-1.png" alt="Istanbul" class="card-image">
              <div class="card-content">
                <h3 class="card-title">Istanbul, Turkey</h3>
                <p class="card-text">Flights • Hotels • Resorts</p>
              </div>
            </div>
            <div class="card">
              <img src="assets/Images/Frame 197-2.png" alt="Sydney" class="card-image">
              <div class="card-content">
                <h3 class="card-title">Sydney, Australia</h3>
                <p class="card-text">Flights • Hotels • Resorts</p>
              </div>
            </div>
            <div class="card">
              <img src="assets/Images/Frame 197.png" alt="Baku" class="card-image">
              <div class="card-content">
                <h3 class="card-title">Baku, Azerbaijan</h3>
                <p class="card-text">Flights • Hotels • Resorts</p>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <div class="fli">
        <div class="cardd">
          <h1 style="font-size: 3rem; margin-bottom: 2px;">Flights</h1>
          <p style="font-size: 1rem; margin-bottom: 20px;">Search Flights & Places – our most popular destinations</p>
          <a href="products.php" class="submit-btn" style="display: inline-flex;">Show Flights</a>
        </div>
        <div class="cardd2">
          <h1 style="font-size: 3rem; margin-bottom: 2px;">Cheap Flights</h1>
          <p style="font-size: 1rem; margin-bottom: 20px;">Special deals to suit your plan</p>
          <a href="products.php" class="submit-btn" style="display: inline-flex;">Show Flights</a>
        </div>
      </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
