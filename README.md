
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

### ...but why?

Among the reason to choose this stack are:

- Laravel Powerful Mail Sending Features: Laravel has a built-in Mail system that offers a rich set of features. It provides a clean API for sending emails with various drivers such as SMTP, Mailgun, SendGrid, and more. Laravel handles email rendering, attachments, inline images, queues, and other advanced functionalities effortlessly.

- Laravel Queue Support: Sending emails synchronously can impact the performance and responsiveness of your application. Laravel's built-in support for queues allows you to offload the email sending process to a separate queue worker, enhancing the overall performance and user experience.

- Laravel rapid Application Development: Laravel's focus on developer productivity and ease of use allows you to rapidly develop your mail sending app. The framework provides a wide range of pre-built functionalities, such as authentication, routing, caching, and more, which saves development time and effort.

- Vue's seamless Integration with Laravel API: Vue.js integrates seamlessly with Laravel APIs. You can use Axios or other HTTP libraries to make API calls from your Vue components and consume the data provided by your Laravel backend. Laravel's API resources and Vue's reactivity work well together, allowing you to efficiently retrieve, update, and display data from your API.

- Vue Router for Single-Page Navigation: Vue Router is the official routing library for Vue.js, and it provides a powerful way to handle client-side navigation in a SPA. You can define routes, nested routes, and route guards to manage the navigation flow of your application. Vue Router integrates well with Laravel's API routes, enabling you to create a seamless navigation experience between views.
## Problem

Create a service that accepts the necessary information and sends emails. It should provide an abstraction between two different email service providers. If one of the services goes down, your service can quickly failover to a different provider without affecting your customers.

## Solution

For this, it was chosen to use a stack formed by Laravel 10 in the backend, in charge of doing the verifications, authentications, sending emails, updating data, etc. and Vue 3, Pinia and Tailwind CSS to create a SPA that is in charge of receiving all the data entered by the user.




## Description

![Architecture Screenshot](https://i.ibb.co/Jk27ZRd/api-spa-architecture.png)

The user enters his data through the SPA, firstly a username and password are required, the user can log in and register through the App, or through the API. Everything is handled in the backend by [Laravel](https://laravel.com/docs/10.x/) and [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum#main-content). 

Sanctum creates an authentication token per user, without which no other part of the application can be accessed, in the forntend this is worked and controlled by Vue Router and Pinia.

A logged in user can access the other features of the service: he can [send emails](https://github.com/Situ314/mail-challenge-backend/blob/b4335403ce2b193fb23f7e8332fa4a961bd34add/app/Http/Controllers/EmailController.php#L33), see the [list of emails](https://github.com/Situ314/mail-challenge-backend/blob/b4335403ce2b193fb23f7e8332fa4a961bd34add/app/Http/Controllers/EmailController.php#L16) that this user sent and also the option to [forward them](https://github.com/Situ314/mail-challenge-backend/blob/b4335403ce2b193fb23f7e8332fa4a961bd34add/app/Http/Controllers/EmailController.php#L62).

The sending of emails is managed through a [queue](https://github.com/Situ314/mail-challenge-backend/blob/master/app/Jobs/SendEmailJob.php) managed by the application's database, so the user doenst need to wait until the emails are sent, since this is a process that can take several seconds. The [job](https://github.com/Situ314/mail-challenge-backend/blob/master/app/Jobs/SendEmailJob.php) in charge of managing the queue sends the email to the recipients one by one, the driver for sending emails is configured to work with the [failover configuration](https://laravel.com/docs/10.x/mail#failover-configuration), within this configuration file, it was specified multiple mail "connections" with different driver settings. When you send an email, Laravel will attempt to use the primary driver specified in the mail configuration, in this case Mailgun. If this one fails to send the email (due to connection issues, API errors, rate limit etc. But most likely on Mailgun because the free version only supported previously registered recievers), the app will automatically fall back to the next one, Google SMTP. If this one fails too, it will try to send the email trough the final driver: Sendgrid. Finally, if all these options failed, the application will send an error message, indicating that it ran out of services to try, so the Email will be saved with a failure status, but don't worry, you can try again send it through the SPA or the API.

Whatever the case, after this is processed, the information is saved in the DB, with a status of queued (the first time, when the email enters the queue), succeed (if the email was sent successfully, then having been processed by the queue) or 'failed' (when the queue tried to process it without success). These states can be verified in the SPA, or also through the API.

## To do

- Attachments
- Option to change the Global sender Email


## Running Tests

To run tests, run the following command

```bash
  php artisan test tests/Unit/EmailTest.php
```


## 🚀 About Me
As a dedicated and passionate web developer, I thrive on creating dynamic and user-friendly websites that combine aesthetics with functionality. With 7+ years of experience in the industry, I possess a strong foundation in web development technologies and a keen eye for detail.

My expertise lies in utilizing PHP frameworks such as Laravel and CodeIgniter to build robust, scalable, and efficient web solutions. I am skilled at developing clean and maintainable code, analyzing requirements, and conducting thorough testing to ensure optimal performance and user satisfaction. Additionally, I have a solid understanding of front-end technologies like HTML, CSS, and JavaScript; and JS frameworks such as React and VueJS enabling me to create seamless user experiences.

[![linkedin](https://img.shields.io/badge/linkedin-0A66C2?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/cdanielarteaga/)
