echo 'itnt-registration backup started'
ROOT=$1/itnt-registration
mkdir $ROOT

# Backup web files
WEBPATH=$ROOT/web
mkdir $WEBPATH
rsync -a . $WEBPATH --exclude .git

# Backup db
DBPATH=$ROOT/db
mkdir $DBPATH
mysqldump -u$2 -p$3 --databases itntregdb > $DBPATH/dump.sql 2> /dev/null
echo 'itnt-registration backup finished'