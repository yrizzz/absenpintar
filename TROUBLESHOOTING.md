# 🔧 Troubleshooting Guide - AbsenPintar

## Common Issues & Solutions

### 1. Alpine.js Errors

#### Error: "Cannot read properties of undefined (reading 'call')"
**Cause:** Livewire not ready when Alpine component initializes

**Solution:** ✅ Fixed! Updated to use `@script` directive and `$wire` instead of `@this`

```javascript
// ❌ Old (causes error)
@this.call('method', data)

// ✅ New (works correctly)
$wire.call('method', data)
```

#### Error: "Detected multiple instances of Alpine running"
**Cause:** Alpine loaded multiple times

**Solution:**
1. Clear browser cache
2. Hard refresh (Ctrl + Shift + R)
3. Check no duplicate Alpine imports

### 2. Migration Errors

#### Error: "Failed to open the referenced table 'branches'"
**Cause:** Migration order incorrect - foreign key references table that doesn't exist yet

**Solution:** ✅ Fixed! Migration order corrected:
```
1. cache
2. branches
3. users
4. shifts
5. device_fingerprints
6. attendance_logs
7. other tables
```

#### Error: "Table 'cache' doesn't exist"
**Cause:** Cache table migration runs after permission migration

**Solution:** ✅ Fixed! Cache migration moved to run first

### 3. Camera Issues

#### Camera not showing
**Possible causes:**
- Browser doesn't support getUserMedia API
- Camera permission denied
- HTTPS required (in production)

**Solutions:**
1. **Check browser support:**
   - Chrome 53+
   - Firefox 36+
   - Safari 11+
   - Edge 12+

2. **Allow camera permission:**
   - Click lock icon in address bar
   - Set camera to "Allow"
   - Refresh page

3. **Use HTTPS in production:**
   ```bash
   # Development (HTTP OK)
   php artisan serve
   
   # Production (HTTPS required)
   # Use proper SSL certificate
   ```

4. **Test camera access:**
   ```javascript
   // Open browser console and test:
   navigator.mediaDevices.getUserMedia({ video: true })
     .then(stream => console.log('Camera OK'))
     .catch(err => console.error('Camera error:', err));
   ```

### 4. GPS/Location Issues

#### Location not detected
**Possible causes:**
- Location permission denied
- GPS disabled
- HTTPS required (in production)
- Browser doesn't support Geolocation API

**Solutions:**
1. **Allow location permission:**
   - Click lock icon in address bar
   - Set location to "Allow"
   - Refresh page

2. **Enable GPS:**
   - Check device GPS is enabled
   - Try outdoor for better signal

3. **Check browser support:**
   ```javascript
   // Test in console:
   if (navigator.geolocation) {
     console.log('Geolocation supported');
   } else {
     console.log('Geolocation NOT supported');
   }
   ```

4. **Test location access:**
   ```javascript
   navigator.geolocation.getCurrentPosition(
     pos => console.log('Location:', pos.coords),
     err => console.error('Location error:', err)
   );
   ```

### 5. Database Issues

#### Connection refused
**Cause:** MySQL not running or wrong credentials

**Solutions:**
1. **Check MySQL status:**
   ```bash
   sudo systemctl status mysql
   # or
   sudo service mysql status
   ```

2. **Start MySQL:**
   ```bash
   sudo systemctl start mysql
   # or
   sudo service mysql start
   ```

3. **Check credentials in .env:**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pulse_presence
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Test connection:**
   ```bash
   mysql -u root -p
   ```

#### Use SQLite instead (easier for development)
```env
DB_CONNECTION=sqlite
# Comment out other DB_ variables
```

```bash
touch database/database.sqlite
php artisan migrate:fresh --seed
```

### 6. Asset Build Issues

#### Vite errors
**Solutions:**
1. **Clear node_modules:**
   ```bash
   rm -rf node_modules package-lock.json
   npm install
   ```

2. **Clear Vite cache:**
   ```bash
   rm -rf node_modules/.vite
   npm run build
   ```

3. **Check Node version:**
   ```bash
   node -v  # Should be 18+ or 20+
   ```

### 7. Session/Auth Issues

#### Logged out unexpectedly
**Possible causes:**
- Session expired
- User status changed to inactive
- Cache issues

**Solutions:**
1. **Clear application cache:**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. **Check user status:**
   ```sql
   SELECT id, name, email, status FROM users WHERE email = 'your@email.com';
   ```

3. **Increase session lifetime in .env:**
   ```env
   SESSION_LIFETIME=120  # minutes
   ```

### 8. Livewire Issues

#### Component not updating
**Solutions:**
1. **Clear Livewire cache:**
   ```bash
   php artisan livewire:delete-uploaded-files
   php artisan view:clear
   ```

2. **Check wire:model bindings:**
   ```blade
   {{-- Make sure property exists in component --}}
   <input wire:model="propertyName">
   ```

3. **Use wire:loading for feedback:**
   ```blade
   <button wire:click="submit" wire:loading.attr="disabled">
       <span wire:loading.remove>Submit</span>
       <span wire:loading>Processing...</span>
   </button>
   ```

### 9. Performance Issues

#### Slow page load
**Solutions:**
1. **Optimize images:**
   - Compress selfies
   - Use appropriate image sizes

2. **Enable caching:**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Use queue for heavy tasks:**
   ```bash
   php artisan queue:work
   ```

### 10. Production Deployment Issues

#### HTTPS required for camera/GPS
**Solution:** Use proper SSL certificate
```nginx
server {
    listen 443 ssl http2;
    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;
    # ... rest of config
}
```

#### File permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Quick Fixes

### Reset Everything
```bash
# Clear all caches
php artisan optimize:clear

# Reset database
php artisan migrate:fresh --seed

# Rebuild assets
npm run build

# Restart server
php artisan serve
```

### Check System Requirements
```bash
# PHP version (need 8.4+)
php -v

# Composer version
composer --version

# Node version (need 18+)
node -v

# NPM version
npm -v

# MySQL version (need 8.0+)
mysql --version
```

### Debug Mode
Enable debug mode in `.env` for detailed errors:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

**⚠️ Remember to disable in production!**

## Getting Help

1. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Browser console:**
   - Press F12
   - Check Console tab for JavaScript errors
   - Check Network tab for failed requests

3. **Database queries:**
   ```bash
   # Enable query log in AppServiceProvider
   DB::enableQueryLog();
   // ... your code
   dd(DB::getQueryLog());
   ```

## Contact Support

If issues persist:
- Check `IMPLEMENTATION_PROGRESS.md` for technical details
- Check `README.md` for setup instructions
- Review migration files for database structure

---

**Most issues can be resolved by:**
1. Clearing caches
2. Rebuilding assets
3. Checking browser permissions
4. Using HTTPS in production
