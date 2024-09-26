## Weather Notification Service - README

### Project Overview
This project is a weather notification service that alerts users based on certain weather conditions (precipitation and UV index thresholds) for their selected cities. Users can pause notifications for a set duration, customize notification thresholds, and receive updates via email or push notifications.

### Features
- **User Authentication**: Powered by Laravel Jetstream and Inertia.js.
- **Weather Notifications**: Alerts for high precipitation or UV index values.
- **Custom Thresholds**: Users can set their own thresholds for weather conditions.
- **Pause Notifications**: Users can pause notifications for a specified number of hours.
- **Multiple Cities**: Users can subscribe to weather alerts for multiple cities.
- **Push Notifications**: Optional push notifications for weather alerts.

### Technologies Used
- **Backend**: Laravel with Docker Sail for local development.
- **Frontend**: Jetstream, Inertia.js, Vue 3, TailwindCSS.
- **Notifications**: Email and push notifications (push via Web Push and Inertia).
- **Weather Services**:
  - **OpenWeatherMap**: Used for weather and precipitation data.
  - **UV Index Service**: External API for UV index data.

### Requirements
- Docker and Docker Compose
- PHP 8.x
- Node.js and npm

### Setup Instructions

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/your-repo/weather-notification-service.git
   cd weather-notification-service
   ```

2. **Run Docker Sail**:
   Ensure Docker is running on your machine, then run:
   ```bash
   ./vendor/bin/sail up
   ```

3. **Install Dependencies**:
   ```bash
   ./vendor/bin/sail composer install
   ./vendor/bin/sail npm install
   ```

4. **Run Migrations**:
   ```bash
   ./vendor/bin/sail artisan migrate
   ```

5. **Generate Application Key**:
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```

6. **Environment Variables**:
   Set up your `.env` file with the necessary environment variables, including OpenWeatherMap API keys.

7. **Build Frontend**:
   ```bash
   ./vendor/bin/sail npm run dev
   ```

### Running the App

- Start the development server using Sail:
  ```bash
  ./vendor/bin/sail up
  ```

- To pause notifications or configure user settings, navigate to the **profile** section in the app.

