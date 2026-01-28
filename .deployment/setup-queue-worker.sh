#!/bin/bash

# FarmGo Queue Worker Setup Script
# Run this script on your production server to setup Supervisor for queue workers

echo "========================================="
echo "FarmGo Queue Worker Setup"
echo "========================================="
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo "❌ Please run as root (use sudo)"
    exit 1
fi

# Install Supervisor
echo "📦 Installing Supervisor..."
apt-get update
apt-get install -y supervisor

# Get project path
read -p "Enter FarmGo project path (e.g., /var/www/farmgo): " PROJECT_PATH

# Validate path
if [ ! -d "$PROJECT_PATH" ]; then
    echo "❌ Directory $PROJECT_PATH does not exist!"
    exit 1
fi

# Get web user
read -p "Enter web server user (default: www-data): " WEB_USER
WEB_USER=${WEB_USER:-www-data}

# Get number of workers
read -p "Enter number of queue workers (default: 2): " NUM_WORKERS
NUM_WORKERS=${NUM_WORKERS:-2}

# Create Supervisor config
echo ""
echo "📝 Creating Supervisor configuration..."

cat > /etc/supervisor/conf.d/farmgo-worker.conf << EOF
[program:farmgo-worker]
process_name=%(program_name)s_%(process_num)02d
command=php $PROJECT_PATH/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=$WEB_USER
numprocs=$NUM_WORKERS
redirect_stderr=true
stdout_logfile=$PROJECT_PATH/storage/logs/worker.log
stopwaitsecs=3600
EOF

# Create log directory if not exists
mkdir -p $PROJECT_PATH/storage/logs
chown -R $WEB_USER:$WEB_USER $PROJECT_PATH/storage/logs

# Reload Supervisor
echo ""
echo "🔄 Reloading Supervisor..."
supervisorctl reread
supervisorctl update
supervisorctl start farmgo-worker:*

# Check status
echo ""
echo "✅ Setup complete! Checking worker status..."
echo ""
supervisorctl status farmgo-worker:*

echo ""
echo "========================================="
echo "Queue Worker Commands:"
echo "========================================="
echo "Check status:  sudo supervisorctl status farmgo-worker:*"
echo "Restart:       sudo supervisorctl restart farmgo-worker:*"
echo "Stop:          sudo supervisorctl stop farmgo-worker:*"
echo "View logs:     tail -f $PROJECT_PATH/storage/logs/worker.log"
echo ""
echo "✨ Done!"
