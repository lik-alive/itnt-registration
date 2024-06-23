# ITNT-Registration

## Info

**Registration service for ITNT conference**

The service helps organize registration process for a scientific conference.

For participants, it provides simple, smart, customizable single-page registration experience.

For organizers, the services gives a rich toolset for monitoring the registration process, collecting statistics and conducting mass mailings. The key task was integration with the EasyChair platform, which allowed access to conference submissions.

The service was deployed on the infrastructure of Samara University, implemented into the management process of ITNT conference and supported from 2019 to 2020.

## Table of Contents
- [Features](#features)
  - [Common](#common)
  - [Functionality](#functionality)
- [Installation](#installation)
  - [For development](#for-development)
  - [For production](#for-production)
  - [For backup](#for-backup)
- [License](#license)

## Features

### Common
- PHP v7.4
- WordPress v5.8.3
- Bootstrap v4.3.1
- jQuery v3.3.1
- MySQL v8
- Internationalization (en, ru)
- Dockerized

### Functionality
- Registration for conference participants
- Bilingual interface (English and Russian)
- Fast search by paperID
- Admin panel
- Statistics of participants, including geolocation
- Integration with the EasyChair platform
- Electronic distribution of participation certificates
- Email preview and delayed sending
- Email templates
- Mass mailing

## Installation

### For development

1. Create `.env`

2. Create `wp-config.php`

3. Fill in the salt in `wp-config.php`
```sh
curl https://api.wordpress.org/secret-key/1.1/salt/
```

4. Deploy
```sh
./deploy.sh
```

5. Create admin user
```sh
docker exec -it dev_itntreg_server php init.php
```

### For production

1. Create `.env`

2. Create `wp-config.php`

3. Fill in the salt in `wp-config.php`
```sh
curl https://api.wordpress.org/secret-key/1.1/salt/
```

4. Change ownership
```sh
sudo chown www-data:www-data .
sudo chown www-data:www-data ./files
sudo chmod 775 .
```

5. Deploy
```sh
./deploy.sh --prod
```

6. Create admin user
```sh
docker exec -it prod_itntreg_server php init.php
```

7. Nginx proxy: don't forget to set Host in global nginx settings
```sh
proxy_set_header Host $http_host;
```

8. Enable email sender
```sh
crontab -e
```

insert:
`*/2 * * * * wget https://website/autosender`


### For backup

1. Install pv
```sh
sudo apt update && sudo apt install pv
```

## License

MIT License