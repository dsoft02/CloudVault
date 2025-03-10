#Secure File Storage System

Secure Storage is a secure file storage system built with Laravel and Storj.io. It ensures end-to-end encryption using hybrid encryption techniques (AES and MCrypt), providing users with a highly secure cloud storage solution.

## Features

- **User Authentication:** Secure login and registration.
- **File Upload with Encryption:** Encrypts files using a user-provided key before uploading to Storj.io.
- **Secure File Sharing:** Generate shareable links with encryption key management.
- **File Decryption & Download:** Only authorized users with the correct key can decrypt and download files.
- **Folder Management:** Organize files into folders and subfolders.
- **Recycle Bin:** Move files to the trash and restore or permanently delete them.
- **Search Functionality:** Find files and folders easily.
- **Temporary File Access:** Generate temporary download links for raw files.

## Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/dsoft02/CloudVault.git
   cd cloudvault
   ```

2. Install dependencies:
   ```sh
   composer install
   npm install
   ```

3. Configure environment variables:
   ```sh
   cp .env.example .env
   php artisan key:generate
   ```
   - Set up database credentials.
   - Configure Storj.io settings in `.env` file.

4. Run migrations:
   ```sh
   php artisan migrate --seed
   ```

5. Start the development server:
   ```sh
   php artisan serve
   ```

## Usage

- **Upload Files:** Users can upload files which are automatically encrypted before being stored.
- **Download Files:** Users must enter the correct decryption key to access their files.
- **Manage Folders:** Organize files using a hierarchical folder structure.
- **Recycle Bin:** Recover deleted files or permanently remove them.
- **Sharing:** Generate encrypted file share links with expiration settings.

## Technologies Used

- Laravel (Backend)
- Storj.io (Cloud Storage)
- OpenSSL & MCrypt (Encryption)
- Bootstrap (Frontend Framework)
- Vue.js (Frontend Enhancements)

