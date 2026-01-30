# AirLugina – Backend (Faza 2)

## Çfarë është shtuar

- **Databaza (MySQL)**: tabela `users`, `news`, `products`, `contact_messages`, `pages`, `slider`
- **PHP OOP**: klasat `Database`, `User`, `Auth`, `News`, `Product`, `Contact`, `Validator`
- **Login / Register** funksional me role: **admin** dhe **user**
- **5 faqe minimale**: index.php (kryefaqja), about.php (About Us), products.php (Products), news.php (News), contact.php (Contact Us)
- **Të dhënat lexohen nga databaza**: faqja kryesore, about, news, products – përmbajtja/slider nga DB
- **Forma e kontaktit** dërgon në DB; administratori i lexon nga **Dashboard**
- **Dashboard admin** (`admin/dashboard.php`): mesazhet e kontaktit, menaxhimi i lajmeve dhe produkteve (shtim/modifikim/fshirje)
- **Cili përdorues ka shtuar/modifikuar** lajm ose produkt shihet në faqe (created_by / updated_by)
- **Validim** në front-end (JS) dhe në backend (PHP – Validator, Auth)
- **Slider** në faqen kryesore (kur ka më shumë se 1 slide në DB)

## Instalimi

1. **MySQL**: krijoni një databazë (ose përdorni emrin nga config).
2. **Konfigurimi**: ndërroni nëse duhet skedarën `config/database.php`:
   - `host`, `dbname`, `username`, `password`
3. **Ekzekutoni instalimin** një herë:
   - Hapni në shfletues: `http://localhost/airluginaprojekt/install.php`
   - Kjo krijon tabelat dhe përdoruesin **admin**.
4. **Kredencialet e adminit**:
   - Email: **admin@airlugina.com**
   - Fjalëkalim: **admin123**
5. Pas instalimit, fshini ose mos e hapni më `install.php` në prod.

## Struktura e shkurtër

- `config/database.php` – konfigurimi i lidhjes me MySQL  
- `database/schema.sql` – skema e tabelave (mund ta importoni edhe nga phpMyAdmin)  
- `classes/` – Database, User, Auth, News, Product, Contact, Validator  
- `init.php` – ngarkon klasat (require në çdo faqe që përdor backend)  
- `login.php`, `signup.php`, `logout.php` – autentifikim  
- `index.php`, `about.php`, `news.php`, `news-detail.php`, `products.php`, `contact.php` – faqe publike  
- `admin/dashboard.php` – paneli i administratorit  
- `admin/news-edit.php`, `news-delete.php`, `product-edit.php`, `product-delete.php` – menaxhim lajme/produkte  

## Përmbajtje statike vs DB

- **Faqja kryesore**: titulli dhe përmbajtja e hero nga tabelat `pages` dhe `slider`; kartat nga `products` (nëse ka).  
- **About Us**: përmbajtja nga tabela `pages` (slug: `about`).  
- **News**: të gjitha lajmet nga `news`; në faqe shihet kush e ka shtuar/përditësuar.  
- **Products**: të gjitha produktet/flights nga `products`; kush e ka shtuar shihet në faqe.  
- **Contact**: forma dërgon në `contact_messages`; administratori i lexon nga Dashboard.

## GitHub

Përdorni GitHub për të përfaqësuar punën (çdo funksion të integruar në front-end dhe back-end), sipas kërkesave të projektit.
