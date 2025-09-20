# 🚀 Railway Deployment Guide - Sistem Perpustakaan Digital SMK

[![Railway](https://img.shields.io/badge/Deploy%20on-Railway-0B0D0E?style=for-the-badge&logo=railway&logoColor=white)](https://railway.app)

> **Complete step-by-step guide to deploy your library system to Railway with public access**

---

## 🎯 **Quick Deployment Steps**

### **1. Push to GitHub (Done ✅)**
```bash
git add .
git commit -m "🚀 Railway deployment ready"
git push origin main
```

### **2. Deploy to Railway**

#### **Step A: Create Railway Account**
1. Go to [railway.app](https://railway.app)
2. Click "Start a New Project"
3. Login with GitHub account

#### **Step B: Deploy Project**
1. Click "Deploy from GitHub repo"
2. Select `muhammadhamja45/Web-Perpustakaan`
3. Railway will auto-detect Laravel project

#### **Step C: Add Database**
1. Click "Add service" → "Database" → "MySQL"
2. Railway will create MySQL database automatically

#### **Step D: Configure Environment Variables**
In Railway dashboard → Settings → Environment:

```env
# Required Variables
APP_NAME=Sistem Perpustakaan Digital SMK
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_NEW_KEY
APP_URL=https://your-app-name.railway.app

# Database (Auto-provided by Railway)
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USERNAME}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Email (Optional - for demo)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=perpustakaan@smk.sch.id
MAIL_FROM_NAME="${APP_NAME}"

# Security
SANCTUM_STATEFUL_DOMAINS=your-app-name.railway.app
```

#### **Step E: Deploy & Run**
1. Railway will auto-deploy
2. Check "Deployments" tab for progress
3. Once deployed, visit your URL!

---

## 🌐 **Expected URLs**

After deployment, your application will be available at:

### **Main Application**
```
https://your-app-name.railway.app
```

### **Key Endpoints**
```
🏠 Home Page:          https://your-app-name.railway.app
📊 Laravel Dashboard:  https://your-app-name.railway.app/dashboard
⚛️ React App:          https://your-app-name.railway.app/react-app
📖 API Documentation:  https://your-app-name.railway.app/api-docs
🔗 API Base:           https://your-app-name.railway.app/api/v1
```

---

## 👥 **Demo User Accounts**

### **Admin Account**
```
Email:    admin@perpustakaan.smk.id
Password: admin123
Role:     Super Admin
```

### **Student Accounts**
```
Email:    ahmad.rizki@student.smk.id
Password: student123
Role:     Student

Email:    siti.nurhaliza@student.smk.id
Password: student123
Role:     Student
```

---

## 📚 **Demo Data Included**

### **Books (12 books)**
- Programming (Laravel, React.js, Python)
- Database (MySQL)
- Security (Cyber Security)
- Design (UI/UX)
- Cloud Computing
- And more...

### **Active Loans**
- 5 active book loans
- Various due dates
- Realistic borrowing patterns

### **Notifications**
- Loan confirmations
- Due date reminders
- New book alerts

---

## 🔧 **Railway Configuration Files**

### **railway.json**
```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS"
  },
  "deploy": {
    "numReplicas": 1,
    "sleepApplication": false,
    "restartPolicyType": "ON_FAILURE"
  }
}
```

### **nixpacks.toml**
```toml
[phases.setup]
nixPkgs = ['nodejs', 'npm']

[phases.install]
cmds = [
    'composer install --no-dev --optimize-autoloader',
    'npm ci --only=production'
]

[phases.build]
cmds = [
    'npm run build',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache'
]

[start]
cmd = 'php artisan serve --host=0.0.0.0 --port=$PORT'
```

### **Procfile**
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
release: php artisan migrate --force
```

---

## 🗄️ **Database Setup**

### **Automatic Migration**
Railway will automatically run:
```bash
php artisan migrate --force
php artisan db:seed --force
```

### **Manual Setup (if needed)**
If auto-setup fails, run manually:
```bash
# In Railway console
php artisan migrate:fresh --force
php artisan db:seed --force --class=DemoDataSeeder
php artisan key:generate
```

---

## 🚀 **Testing Your Deployment**

### **1. Basic Functionality**
✅ Homepage loads
✅ Registration works
✅ Login successful
✅ Dashboard accessible

### **2. API Testing**
```bash
# Test API
curl https://your-app-name.railway.app/api/v1/books

# Test Search
curl https://your-app-name.railway.app/api/v1/books/search?q=laravel

# Test Recommendations
curl https://your-app-name.railway.app/api/v1/recommendations/popular
```

### **3. React App Testing**
✅ React app loads at `/react-app`
✅ Search functionality works
✅ Notifications center works
✅ Book recommendations display

---

## 🔧 **Troubleshooting**

### **Common Issues**

#### **1. Database Connection Error**
```bash
# Check environment variables
echo $DB_HOST
echo $DB_DATABASE

# Verify MySQL service is running
```

#### **2. Migration Fails**
```bash
# Force fresh migration
php artisan migrate:fresh --force
```

#### **3. App Key Missing**
```bash
# Generate new key
php artisan key:generate --force
```

#### **4. React App Not Loading**
```bash
# Check build process
npm run build
```

### **Debug Commands**
```bash
# Check logs
railway logs

# Connect to database
railway connect MySQL

# Run artisan commands
railway run php artisan migrate
```

---

## 📊 **Performance Optimization**

### **Railway Free Tier Limits**
- ✅ **RAM**: 512MB (sufficient)
- ✅ **CPU**: Shared (good for demo)
- ✅ **Storage**: 1GB (enough)
- ✅ **Bandwidth**: 100GB/month (generous)

### **Optimization Settings**
```env
# Performance
APP_DEBUG=false
LOG_LEVEL=error
CACHE_STORE=database
SESSION_DRIVER=database
```

---

## 🔒 **Security Settings**

### **Production Security**
```env
APP_ENV=production
APP_DEBUG=false
SANCTUM_STATEFUL_DOMAINS=your-app-name.railway.app
SESSION_SECURE_COOKIE=true
```

### **CORS Configuration**
```php
// Auto-configured for Railway domain
'allowed_origins' => ['https://your-app-name.railway.app']
```

---

## 📱 **Mobile Access**

Your app will be fully accessible on mobile devices:
- ✅ Responsive design
- ✅ Touch-friendly interface
- ✅ Fast loading
- ✅ Works offline (basic functionality)

---

## 🎯 **Demo Features to Showcase**

### **1. Laravel Web App**
- 2FA Authentication
- Book Management
- Loan System
- Admin Dashboard
- Email Notifications

### **2. API Endpoints**
- RESTful API
- Advanced Search
- Recommendations
- Real-time Notifications

### **3. React Frontend**
- Modern UI/UX
- Real-time Search
- Notification Center
- Book Recommendations

---

## 📞 **Support & Monitoring**

### **Railway Dashboard**
- View deployment logs
- Monitor resource usage
- Scale if needed
- Custom domain setup

### **Application Monitoring**
```
📊 App Metrics:  https://your-app-name.railway.app/telescope
🗄️ Database:     Railway MySQL dashboard
📧 Email Logs:   Laravel log viewer
```

---

## 🔄 **Continuous Deployment**

Railway automatically redeploys when you push to GitHub:

```bash
# Make changes
git add .
git commit -m "Update feature"
git push origin main

# Railway auto-deploys! 🚀
```

---

## 💰 **Cost Estimation**

### **Railway Free Tier**
- ✅ **Cost**: $0/month
- ✅ **Includes**: Web app + MySQL database
- ✅ **Perfect for**: Demo and development

### **Upgrade Options**
- 💰 **Pro**: $5/month (more resources)
- 🏢 **Team**: $20/month (team features)

---

## 🎉 **Final Checklist**

Before going live:

- [ ] ✅ Push latest code to GitHub
- [ ] ✅ Create Railway project
- [ ] ✅ Add MySQL database
- [ ] ✅ Configure environment variables
- [ ] ✅ Deploy and test
- [ ] ✅ Verify all features work
- [ ] ✅ Share public URL

---

## 🌟 **Expected Result**

After successful deployment, you'll have:

🌐 **Public URL**: `https://your-app-name.railway.app`
👥 **Demo Users**: Ready to login
📚 **Sample Data**: 12 books, active loans
🔔 **Notifications**: Working notification system
⚛️ **React App**: Modern frontend at `/react-app`
📖 **API Docs**: Complete documentation at `/api-docs`

---

**🚀 Ready to Deploy?**

Just follow the steps above and your library management system will be live and accessible to the public within 10 minutes!

**📞 Need Help?**
- Railway Documentation: [docs.railway.app](https://docs.railway.app)
- Railway Discord: [discord.gg/railway](https://discord.gg/railway)

---

**🎯 Quick Deploy Button:**

[![Deploy on Railway](https://railway.app/button.svg)](https://railway.app/new/template/your-template-id)

---