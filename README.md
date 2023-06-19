
![Logo](https://i.ibb.co/F539HdJ/header-woowup-mailer.png)


## Authors

- [Woowup Mailer Challenge SPA](http://situarts.com/)
- [Woowup Mailer Challenge API DOC](147.182.181.166)
- [Woowup Mailer Challenge Backend Code](https://github.com/Situ314/mail-challenge-backend)
- [Woowup Mailer Challenge Frontend Code](https://github.com/Situ314/woowup-challenge-frontend)


# Woowup Challenge App

Service that allows sending emails made for the Woowup Challenge 2023 for the position of Backend developer.



## Tech Stack

**Backend:** Laravel 10

**Frontend:** Vue 3, Pinia, Tailwind CSS


## Problem

Create a service that accepts the necessary information and sends emails. It should provide an abstraction between two different email service providers. If one of the services goes down, your service can quickly failover to a different provider without affecting your customers.

## Solution

For this, it was chosen to use a stack formed by Laravel 10 in the backend, in charge of doing the verifications, authentications, sending emails, updating data, etc. and Vue 3, Pinia and Tailwind CSS to create a SPA that is in charge of receiving all the data entered by the user.




## Architecture

![Architecture Screenshot](https://i.ibb.co/x3N0ThS/woowup-architecture.png)

The user enters his data through the SPA, firstly a username and password are required, the user can log in and register through the App, or through the API. Everything is handled in the backend by [Laravel](https://laravel.com/docs/10.x/) and [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum#main-content). 

Sanctum creates an authentication token per user, without which no other part of the application can be accessed, in the forntend this is worked and controlled by Vue Router and Pinia.

A logged in user can access the other features of the service: he can [send emails](https://github.com/Situ314/mail-challenge-backend/blob/b4335403ce2b193fb23f7e8332fa4a961bd34add/app/Http/Controllers/EmailController.php#L33), see the [list of emails](https://github.com/Situ314/mail-challenge-backend/blob/b4335403ce2b193fb23f7e8332fa4a961bd34add/app/Http/Controllers/EmailController.php#L16) that this user sent and also the option to [forward them](https://github.com/Situ314/mail-challenge-backend/blob/b4335403ce2b193fb23f7e8332fa4a961bd34add/app/Http/Controllers/EmailController.php#L62).

The sending of emails is managed through a [queue](https://github.com/Situ314/mail-challenge-backend/blob/master/app/Jobs/SendEmailJob.php) managed by the application's database, so the user doenst need to wait until the emails are sent, since this is a process that can take several seconds. The [job](https://github.com/Situ314/mail-challenge-backend/blob/master/app/Jobs/SendEmailJob.php) in charge of managing the queue sends the email to the recipients one by one, the driver for sending emails is configured to work with the [failover driver](https://laravel.com/docs/10.x/mail#failover-configuration), this means that according to an array given in the Laravel configuration it will grab the services in the established order (in this case Mailgun-Sendgrid), so that if one fails, it is the other service that grabs this task, and so on until reaching the end of the array, if this is the case and none of the services worked, the Job fails. 

Whatever the case, after this is processed, the information is saved in the DB, with a status of queued (the first time, when the email enters the queue), succeed (if the email was sent successfully, then having been processed by the queue) or 'failed' (when the queue tried to process it without success). These states can be verified in the SPA, or also through the API.

## 🚀 About Me
As a dedicated and passionate web developer, I thrive on creating dynamic and user-friendly websites that combine aesthetics with functionality. With 7+ years of experience in the industry, I possess a strong foundation in web development technologies and a keen eye for detail.

My expertise lies in utilizing PHP frameworks such as Laravel and CodeIgniter to build robust, scalable, and efficient web solutions. I am skilled at developing clean and maintainable code, analyzing requirements, and conducting thorough testing to ensure optimal performance and user satisfaction. Additionally, I have a solid understanding of front-end technologies like HTML, CSS, and JavaScript; and JS frameworks such as React and VueJS enabling me to create seamless user experiences.

[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/cdanielarteaga/)
