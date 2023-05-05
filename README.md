# О Kykyshka PHP SDK
Предназначен для работы с CPA-сетью сервиса **Kykyshka**: отслеживания переходов по ссылкам из опросов, регистрации целевых действий и подтверждения выплат

## Использование
Включите в свой проект файл `kykySDK.php`, создайте объект класса `kykySDK` и используйте его методы:
```php
include("kykySDK.php"); //подключение SDK

$kyky=new kykySDK(); //создание объекта SDK

$kyky->checkToken(your_kyky_token);	//проверяет валидность токена просмотра опроса

$kyky->register(your_kyky_token);	//регистрирует выполнение целевого действия (покупка, регистрация и т.п)

$kyky->approve(your_kyky_token);	//подтверждает выплату по зарегистрированному ранее целевому действию. Чем больше подтверждений, тем больше пользователей увидят ваш опрос!
```

## Методы
### checkToken
Проверяет валидность токена просмотра опроса
```php
$kyky->checkToken(your_kyky_token);
```
Содержимое ответа:<br>
| Ключ | Значение |
| --- | --- |
| `kyky_token` | Искомый токен |
| `is_valid` | Валиден ли токен (true/false) |
| `time` | Время перехода по ссылке (если токен не валиден, то `null`) |
| `action_performed` | Было ли совершено целевое действие (true/false) |
| `performed_date` | Время совершения целевого действия (если оно не совершено, то `null`) |
| `action_approved` | Была ли подтверждена выплата по целевому действию (true/false) |
| `approved_date` | Время подтверждения выплаты (если она не подтверждена, то `null`) |
### register
Регистрирует целевое действие, подписанное токеном просмотра опроса
```php
$kyky->register(your_kyky_token);
```
Содержимое ответа:<br>
| Ключ | Значение |
| --- | --- |
| `kyky_token` | Искомый токен |
| `result` | Было ли зарегистрировано целевое действие в результате вызова функции (true/false) |<br>

Попытка регистрации невалидного токена или повторная регистрация зарегистрированного ранее токена вернёт ошибку с кодом 100
### approve
Подтверждает зарегистрированное ранее целевое действие (выплату по нему), подписанное токеном просмотра опроса
```php
$kyky->approve(your_kyky_token);
```
Содержимое ответа:<br>
| Ключ | Значение |
| --- | --- |
| `kyky_token` | Искомый токен |
| `result` | Была ли подтверждена выплата в результате вызова функции (true/false) |<br>

Попытка подтверждения невалидного или незарегистрированного ранее методом `register` токена, а также повторное подтверждение зарегистрированного ранее токена вернёт ошибку с кодом 100
## Ошибки
В ходе работы SDK могут возникать ошибки. При возникновении ошибки в ответе метода будут дополнительно возвращены поля:<br>
| Ключ | Значение |
| --- | --- |
| `error_code` | Код ошибки |
| `reason` | Описание ошибки |

### Расшифровка кодов ошибок
| Код ошибки | Описание |
| --- | --- |
| 0 | Внутренняя ошибка, повторите запрос позже |
| 1 | Превышен лимит запросов к API (не более 100 вызовов в минуту), повторите запрос позже |
| 100 | Токен kyky_token недействителен или метод API не может быть выполнен относительно токена (для методов `register`и `approve`) |
| 101 | Токен kyky_token не передан методу API |
## Где брать kyky_token
**kyky_token** — уникальный идентификатор (токен) просмотра опроса. При переходе по внешней ссылке из опроса он передаётся в неё через параметры запроса (по умолчанию в одноимённом параметре `kyky_token`). Его следует отслеживать для того, чтобы определить, что пользователь пришёл из Кукушки.<br><br>
kyky_token не является постоянным идентификатором, определить по нему пользователя в нескольких разных опросах нельзя. Он предназначен именно для пометки конкретной заявки/лида, а не пользователя.<br>
![Типовой пример работы с kyky_token: токен передаётся в ссылке на страницу с формой и сохраняется вместе с данными из заполненной формы](/readme/help.png)<br>
*Типовой пример работы с kyky_token: токен передаётся в ссылке на страницу с формой и сохраняется вместе с данными из заполненной формы*
## Важно
Регистрация и подтверждение выполнения целевого действия влияют на *процент подтверждений*, который считается по формуле<br><br>
**P = A / R**,<br><br>
где P — процент подтверждений,<br>
A — число целевых действий, подтверждённых методом `approve`,<br>
R — число целевых действий, зарегистрированных методом `register`<br><br>Чем выше процент подтверждений, тем большему числу пользователей будет показан ваш опрос!
