# SPIEGAZIONE PROGETTO


## BE


### Consumare una api pubblica qualsiasi (sei libero di scegliere un servizio a piacere, ad esempio come https://any-api.com/ o https://apilist.fun/) e aggregare i dati ricevuti in qualche modo

E' stata creata un api usando come base le seguenti api fake <a href="https://fakestoreapi.com/docs">Api</a>. utilizzando come api per il test <strong>"products"</strong>.
E' stato creato il controller <pre>App\Controller\ConsumerController::index</pre> <br /> dove è stata creata l'api esposta in post; di seguito un esempio di come deve essere invocata <br />
<pre>
curl --request POST \
  --url 'http://localhost:8000/api/consumer?page=0&size=1&sortBy=title&order=desc' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/8.1.0' \
  --data '{
	"title": "Fjallraven ",
	"description": null
}'
</pre>.
<br />La response è stata aggregata creando un oggetto Pageable simile a quello di SpringBoot.

  ***

  ### Prevedere uno scope per i dati salvati (pubblico/autenticato/entrambi)

  Basandomi sull'api precedente, si è creata la medesima api, derivante dal controller <br />
  <pre>App\Controller\ConsumerController::auth</pre>
  <br />
  In questo  caso nell'header è richiesto il campo <strong>Autorizzation</strong>, valorizzato con una stringa in base64 ottenuta dalla concatenazione dello username e password uniti con ":".
  Un esempio di curl:
  <pre>
  curl --request POST \
  --url 'http://localhost:8000/auth/consumer?page=0&size=1&sortBy=title&order=desc' \
  --header 'Autorizzation: 556465564654654645' \
  --header 'Content-Type: application/json' \
  --header 'User-Agent: insomnia/8.1.0' \
  --data '{
	"title": null,
	"description": null
}
  </pre>
  <br />In questo caso è stato usato un evento per la verifica della presenza dell'autorizzazione per la chiamata api. Tale file si trova in:<pre>App\Common\EventAuth::validateToken</pre>.
  <br />
  <strong>NOTA:</strong><i>I dati utenti per i test possono essere estrapolati dalla seguente api <a href="https://fakestoreapi.com/user">User</a>, riaggregata semplificando i valori; l'api in essere è <pre>App\Controller\ConsumerController::user</pre> e, di seguito un esempio di curl:</i>
<pre>
<p style="font-weight:900">Senza ricerca dell'utente<p>
curl --request GET \
  --url http://localhost:8000/api/user \
  --header 'User-Agent: insomnia/8.1.0'
  <p style="font-weight:900">Con ricerca dell'utente<p>
  curl --request GET \
  --url http://localhost:8000/api/user/am9obmQ6bTM4cm1GJA== \
  --header 'User-Agent: insomnia/8.1.0'
</pre>


  ***

  