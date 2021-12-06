## Laravel Estate Agent Appointment API

It is an example API project that allows an estate agent to show records of when and to which client their employees will show their appointments. This API project shows how long the employees go to and from the appointments, makes it possible to check the time they allocate for the appointment and all these appointments without conflicts among themselves.

### Installation

- Clone the project
```bash
git clone git@github.com:n1crack/estateagent.git estateagent
```
- copy example env file
```bash
cp .env.example .env
```
- Install dependencies
```bash
composer install
```
- generate app key
```bash
php artisan key:generate
```
- generate JWT Secret Key
```bash
php artisan jwt:secret
```
- create  an sqlite file / or you can set a different database in the .env file.
```bash
touch database/database.sqlite
```
- run database migrations
```bash
php artisan migrate
```
- get a Graphhopper api key and set the .env file.
```dotenv
API_GRAPHHOPPER_KEY=
```
### Endpoints

| API Endpoint          | Method | Description        | Parameters                                                                                                                                                            |
|-----------------------|--------|--------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| /api/auth/login       | POST   | user login         | email : staff email<br/>password : <user_pass>                                                                                                                        |
| /api/auth/logout      | POST   | user logout        | -                                                                                                                                                                     |
| /api/auth/register    | POST   | user register      | name, email and password                                                                                                                          |
| /api/auth/refresh     | POST   | Refresh token      | -                                                                                                                                                                     |
| /api/auth/me          | POST   | user info          | -                                                                                                                                                                     |
| /api/appointment      | GET    | appointment index  | -                                                                                                                                                                     |
| /api/appointment      | POST   | create Appointment | name : customer name<br/>surname : customer surname<br/>phone: customer phone<br/>email : customer email<br/>address : appointment address<br/>date : appointment date |
| /api/appointment/{id} | GET    | show appointment   | -                                                                                                                                                                     |
| /api/appointment/{id} | PATCH  | update appointment | address : appointment address<br/>date : appointment date                                                                                                             |
| /api/appointment/{id} | DELETE | delete appointment | -                                                                                                                                                                     |




