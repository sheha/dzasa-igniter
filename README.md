lista uradjenog
CONFIG:
    base_url
    logging_threshold
    encryption_key postavljen (generisan manuelno)
    session moved to APPPATH . 'sessions', away from index in root
    objekat sesije se snima u folder koji je ispod roota, onemogucen access - kofol security
    cookie domain - jok
    CSRF Prot - jok
    database config - ok
    strict mode on
    MOD REWRITE - enabled, index.php disposed of(fuuuj - al ovo napati)
    user management working


EVO MOJ virtualhost za apache2

    <VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /vagrant/src
        LogLevel debug

        ErrorLog /var/log/apache2/error.log
        CustomLog /var/log/apache2/access.log combined

        <Directory /vagrant/src>
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>
    

- Ja sam na vagrantu pa ti stavi svoj doc root /var/www/phonebook, ako bude kakvih problema.
Vjerovatno ti je AllowOverride u apache2.confu za /var/www/, to je obavezno za rewrite rules  
Login imam, radim na prikazu imenika, bice datatable kontrola tu (https://datatables.net/download/ - pogledaj, bas je
 lijepa ima modale za edit polja i sl).Bice pravo reprezentativno
 Nije obicni login nego, imas forgot pass, registraciju, aktivaciju/deaktivaciju korisnika
 Eto pogledaj ako ti valja smjer u kojem ide, ako ne valja reci gdje ne valja

 OVO STO SAD COMMITAM JE NEFUNKCIONALNO, AKO HOCES DA VIDIS KAKO FERCERA login, POVUCI PRETHODNU VERZIJU, PRVI COMMIT
VECINA LIBRARIJA SE UCITAVAJU SA CDN-A, SAMO ONE KOJE NISU HOSTANE SU LOKALNO U ASSETSIMA

Eh Eto, Ismar