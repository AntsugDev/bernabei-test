## Configurazioni

<ul>
<li>Versione di symfony  6.3</li>
<li>Versione node/npm 18</li>
</ul>

***

## Obiettivo

L'obiettivo è stato quello di usare a pieno le funzionalità di symfony e di sfruttare le sue capacità.


***

## Progetto

Il progetto si compone di due parti:
<ul>
<li>BE</li>
<li>FE</li>
</ul>

La prima parte vede la realizzazione di due api:

<ul>
<li>/api/consumer</li>
<li>/api/user/{id}</li>
<li>/auth/consumer</li>
<li>/api/base64</li>
</ul>

Per le api come base si sono utilizzate le seguenti api mockup presenti a questo indirizzo:

<ul>
<li><a href="https://fakestoreapi.com/docs">Documentazione</a></li>
<li><a href="https://fakestoreapi.com/products">/api/consumer e /auth/consumer</a></li>
<li><a href="https://fakestoreapi.com/users">/api/user/{id}</a></li>
</ul>


La parte FE invece consiste in due Controller:

<ul>
<li><a href="src/Controller/LoginController.php">Login</a></li>
<li><a href="src/Controller/HomeController.php">Home</a></li>
</ul>

***
### BE
#### /api/consumer

Questa api restitutisce una lista di progetti.
L'api viene chiamata in <i>Post</i> senza nessuna autorizzazione lato header.
<p><strong>Esempio di request</strong></p>
<pre>
curl --request POST \
  --url 'http://localhost:8000/api/consumer?page=1&size=5&sortBy=title&order=desc' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/8.1.0' \
  --data '{
	"title": null,
	"description": null
}'
</pre>

<p><strong>Esempio di response</strong></p>
<pre>
{
	"content": [
		{
			"id": 7,
			"title": "White Gold Plated Princess",
			"price": 9.99,
			"description": "Classic Created Wedding Engagement Solitaire Diamond Promise Ring for Her. Gifts to spoil your love more for Engagement, Wedding, Anniversary, Valentine's Day...",
			"category": "jewelery",
			"image": "https:\/\/fakestoreapi.com\/img\/71YAIFU48IL._AC_UL640_QL65_ML3_.jpg",
			"rating": {
				"rate": 3,
				"count": 400
			}
		}
	],
	"page": 0,
	"totalPage": 20,
	"sortBy": "title",
	"order": "desc",
	"totalElements": 20
}
</pre>

Rispetto all'api originale, quest'ultima è stata lavorata per rendera simile alla paginazione in SpringBoot (vedi <a href="https://www.baeldung.com/spring-data-jpa-pagination-sorting">Esempio</a>). 


#### /api/user/{id}

Questa api restitutisce una lista di utenti o il singolo utente qualora sia valorizzato nel pathVariable il campo id (<i><strong>username+':'+password in base64</strong></i>).
L'api viene chiamata in <i>Get</i>.
<p><strong>Esempio di request</strong></p>
<pre>
curl --request GET \
  --url http://localhost:8000/api/user \
  --header 'User-Agent: insomnia/8.1.0'
</pre>
La response dell'api è stata rilavorata rispetto all'originale, in modo da restiure solo i campi fondamentali per la login.
<p><strong>Esempio di response</strong></p>
<pre>
[
	{
		"id": 1,
		"username": "johnd",
		"pwd": "m38rmF$",
		"firstaname": "john",
		"lastname": "doe"
	}
]
</pre>

### /auth/consumer

La base di questa api è la stessa dell' api <i>/api/consumer</i>, con la differenza che nell'header viene richiesto il campo <strong>Autorizzation</strong> uguale a <strong>username+':'+password</strong>.

<p><strong>Esempio di request</strong></p>
<pre>
curl --request POST \
  --url 'http://localhost:8000/auth/consumer?page=0&size=1&sortBy=title&order=desc' \
  --header 'Autorizzation: 556465564654654645' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/8.1.0' \
  --data '{
	"title": null,
	"description": null
}'
</pre>

### /api/base64

Questa api serve per poter estrarre il valore in base64 necessario per le api <i>/auth/consumer</i> e <i>/api/user/{id}</i>.
L'api viene chiamata in <i>Post</i>.


<p><strong>Esempio di request</strong></p>
<pre>
curl --request POST \
  --url http://localhost:8000/api/base64 \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/8.2.0' \
  --data '{
	"username": "johnd",
	"password": "m38rmF$"
}'
</pre>
<p><strong>Esempio di response</strong></p>
<pre>
{
	"requestOriginal": "johnd:m38rmF$",
	"base64": "am9obmQ6bTM4cm1GJA==",
	"timeRequest": "06\/10\/2023 10:03:45"
}
</pre>

***
### FE

### Login

La pagina in essere si presenta con un classico form di Login, composto da username e password.
Il form è stato realizzato attraverso le funzionalità di symfony (<i>Form e Entity</i>), sfuttando la funzione di submit e la funzione di estrazione dei dati del form.
Nello specifico i due file interessati:
<ul>

<li><a href="src/Entity/Login.php">Entity</a></li>
<li><a href="src/Form/LoginFormType.php">Form</a></li>

</ul>

### Home

La pagina di home si presenta con una tabella dei progetti e un form di ricerca; il form contiene:
<ul>
<li>Nr. di pagina</li>
<li>Elementi per pagina</li>
<li>ordinamento</li>
<li>Campo di ordinamento</li>
<li>Ricerca per titolo</li>
<li>Ricerca per descrizione</li>
</ul>

Ogni volta viene premuto il pulsante "Cerca", il sistema submit il form e ricarica la pagina secondo i nuovi filtri.

***