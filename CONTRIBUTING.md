# Contributing to BKK Backend

Thank you for considering contributing to BKK Backend! This document provides guidelines for contributing to the project.

## How to Contribute

### Reporting Bugs

Before creating bug reports, please check the existing issues to avoid duplicates. When creating a bug report, include as many details as possible:

- Use a clear and descriptive title
- Describe the exact steps to reproduce the problem
- Provide specific examples to demonstrate the steps
- Describe the behavior you observed and what you expected to see
- Include logs, error messages, and screenshots if applicable
- Specify your environment (OS, Docker/Podman version, PHP version, etc.)

### Suggesting Features

Feature requests are welcome! Please:

- Use a clear and descriptive title
- Provide a detailed description of the proposed feature
- Explain why this feature would be useful
- Include examples of how the feature would work

### Pull Requests

1. Fork the repository
2. Create a new branch from `main`:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Make your changes
4. Test your changes:
   ```bash
   podman-compose exec app php artisan test
   ```
5. Commit your changes with clear commit messages
6. Push to your fork
7. Open a Pull Request

## Development Setup

### Prerequisites

- Docker 20.10+ or Podman 4.0+
- Git

### Getting Started

```bash
# Clone the repository
git clone https://github.com/fauzymadani/bkk-backend.git
cd bkk-backend

# Start containers
podman-compose up -d

# The application will auto-initialize
```

### Running Tests

```bash
podman-compose exec app php artisan test
```

### Code Style

This project follows Laravel coding standards. Run Pint to format your code:

```bash
podman-compose exec app ./vendor/bin/pint
```

## Project Structure

```
bkk-backend/
├── app/                    # Application code
├── config/                 # Configuration files
├── database/               # Migrations and seeders
├── docker/                 # Docker configuration
├── routes/                 # Route definitions
├── tests/                  # Test files
└── docker-compose.yml      # Docker services
```

## Commit Message Guidelines

- Use the present tense ("Add feature" not "Added feature")
- Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit the first line to 72 characters
- Reference issues and pull requests after the first line

Example:
```
Add Docker support for development

- Create Dockerfile for PHP 8.3
- Add docker-compose.yml with MariaDB
- Configure nginx for Laravel
- Add entrypoint script for auto-initialization

Fixes #123
```

## Questions?

Feel free to open an issue with the "Question" label if you have any questions about contributing.

## License

By contributing, you agree that your contributions will be licensed under the same license as the project (MIT License).
