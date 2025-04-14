# Headless Vue + WordPress Boilerplate
This boilerplate allows you to use Vue as the front-end for your WordPress theme in a headless architecture. WordPress handles the backend and REST API, while Vue powers the user interface using modern frontend tooling like Vite.

## 🧠 Key Features
- 🌟 Use Vue 3 with Vite for a fast and modern development experience

- 🔌 Connects to WordPress REST API for dynamic content

- 🗂 Modular folder structure for components, pages, APIs, and stores

- ♻️ Supports Vue Router and Pinia (Vue Store)

- 🎨 Style with standard CSS or your preferred CSS preprocessor

## 📁 Folder Structure
```bash
themes/
└── vue-boilerplate/
    ├── functions/               # WordPress PHP functions
    ├── public/                  # Public assets
    ├── src/                     # Vue application root
    │   ├── api/                 # JS modules for API calls
    │   │   └── wp/
    │   ├── assets/              # Static assets like images and SVGs
    │   ├── components/          # Vue components (e.g., layout)
    │   ├── page/                # Vue page views
    │   ├── router/              # Vue Router setup
    │   └── stores/              # Pinia stores
    │       └── wp/
    │   ├─── App.vue             # Root Vue component
    │   ├─── main.js             # Vue app entry point
    │   └─── style.css           # Global styles
    ├── index.html               # Entry HTML for the Vue app
    ├── *.php                    # WordPress theme files (header.php, footer.php, etc.)
    ├── style.css                # Wordpress Global styles + Theme metadata
    ├── vite.config.js           # Configuration file for Vite, a fast frontend build tool.
```
## 🚀 Getting Started
1. Download and put the files inside your `themes` folder.
[Download Latest Release](https://github.com/chrischase011/wordpress-vue-theme-boilerplate/releases)

2. Install Dependencies
```bash
npm install
```
3. Run the Development Server
```bash
npm run start
```

This will start the Vite development server and compile your Vue frontend.

> Make sure WordPress is running locally (e.g., with LocalWP or Laragon) and that the theme is activated.

## 📦 Build for Production
```bash
npm run build
```

This will compile and optimize your Vue app for production inside the WordPress theme.

## 🛠 Integration Tips
- Use the functions.php file to enqueue the compiled assets

- Use WordPress routes to serve the base HTML (`index.php`)

- Connect your Vue app to the WP REST API through `src/api/wp/`



<br><br>

# Vue 3 + Vite

This template should help get you started developing with Vue 3 in Vite. The template uses Vue 3 `<script setup>` SFCs, check out the [script setup docs](https://v3.vuejs.org/api/sfc-script-setup.html#sfc-script-setup) to learn more.

Learn more about IDE Support for Vue in the [Vue Docs Scaling up Guide](https://vuejs.org/guide/scaling-up/tooling.html#ide-support).



Prepared by: [Christopher Robin Chase](https://github.com/chrischase011)