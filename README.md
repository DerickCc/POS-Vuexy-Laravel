#Cara Setup Website (Untuk Pertama Kali)
1. Buka Terminal pada root directory
2. Ketik 'composer install'
3. Lalu copy file .env.example dan paste, rename menjadi .env
4. Kemudian ketik 'php artisan key:generate'
5. Ketik 'yarn', jika belum menginstall yarn, maka ketik 'npm install --global yarn' terlebih dahulu
6. Terakhir 'php artisan migrate'

#Cara Menjalankan Website (Development)
1. Buka Terminal pada root directory
2. Ketik 'php artisan serve'
3. Buka Terminal baru pada root directory
4. Ketik 'yarn dev'

Jika website sudah selesai di-develop, maka ketik 'yarn build' pada terminal untuk build dependency dan file-file lain yang dibutuhkan untuk menjalankan website.
