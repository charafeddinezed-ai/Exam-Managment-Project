1. Clone the repository
git clone https://github.com/username/Exam-Managment-Project.git
cd Exam-Managment-Project

2. Install PHP dependencies
Run:

cd backend

composer install

3. Set up environment variables

Copy the example environment file:

cp .env.example .env


Then edit .env with your database and app settings.

4. Generate the Laravel app key
php artisan key:generate

5. Run migrations (optional)
php artisan migrate

For Vite + React (frontend)

React/Vite projects ignore the node_modules/ folder (which contains all JavaScript dependencies).

6. Go into your frontend directory (if applicable)

Example:

cd frontend

7. Install Node.js dependencies

Run:

npm install
# or if the project uses yarn:
yarn


This recreates the node_modules/ folder.

8. Build or run the development server
npm run dev
# or
npm run build
