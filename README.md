# Small Banking Application 😊

This is a small banking application built with PHP using the Laravel framework. It allows users to perform various banking operations such as registration, login, cash deposit, cash withdrawal, cash transfer, account statement viewing, and logout.

## Features 🚀

1. **Registration**: Users can create a new account with their email id and password.
2. **Login**: Existing users can log in to their accounts securely.
3. **Inbox/Home**: Users can view their account information.
4. **Cash Deposit**: Users can deposit some amount into their accounts.
5. **Cash Withdrawal**: Users can withdraw some amount from their accounts.
6. **Cash Transfer**: Users can transfer some amount from their account to another user's account using email id.
7. **Account Statement**: Users can view their account statements to track their transactions.
8. **Logout**: Users can securely logout from their accounts.

## Routes 🛣️

- **Authentication Routes**:
  - `POST /login`: Endpoint to login.
  - `POST /register`: Endpoint to register a new account.
  - `POST /logout`: Endpoint to logout.
  - `POST /refresh`: Endpoint to refresh authentication token.

- **Transaction Routes**:
  - `POST /deposit`: Endpoint to deposit cash.
  - `POST /withdraw`: Endpoint to withdraw cash.
  - `POST /transfer`: Endpoint to transfer cash.
  - `GET /statement`: Endpoint to view account statement.

- **Home Route**:
  - `GET /home`: Endpoint to view home/inbox.

## Installation ⚙️

1. Clone the repository: `git clone <repository-url>`
2. Install dependencies: `composer install`
3. Set up database configurations in `.env` file
4. Run migrations: `php artisan migrate`
5. Serve the application: `php artisan serve`

## Usage 📝

1. Register a new account using `/register` endpoint.
2. Log in to your account using `/login` endpoint.
3. Perform banking operations such as deposit, withdrawal, transfer, and view statement using respective endpoints.
4. Log out using `/logout` endpoint.

## Contribution 🤝

Contributions are welcome! Feel free to open issues and pull requests.

## License 📄

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
