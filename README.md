# SwagTrainingSendEmail

To test out an email via MailTrap, set the configuration **Preferred email agent** in the Administration (via Settings > System > Mailer) to **Use environment's configuration**.

Next, add `MAILER_URL` to your `.env` file (where `foo` and `bar` are your Mailtrap credentials):

    MAILER_URL=smtp://foo:bar@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login

Note that the Symfony docs mention to use `MAILER_DSN` but in Shopware this is `MAILER_URL`.
