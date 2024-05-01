# Use an official Node.js image as the base image
FROM node:current-alpine

# Set the working directory in the container
WORKDIR /app

# Copy package.json and package-lock.json to the working directory
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the application code to the working directory
COPY . .

# Build the React app for production
RUN npm run build

# Expose port 80 to allow outside access to the React app
EXPOSE 80

# Command to run the React app when the container starts
CMD ["npx", "serve", "-s", "-l", "80", "build"]
