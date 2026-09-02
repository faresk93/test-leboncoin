# test-leboncoin

test leboncoin suite à l'entretien RH

### comment tester:
- lancer `docker compose up --build`
- appeler les API (`/api/list` & `/api/stats`) avec la base URI http://localhost:8080
  - GET `/api/list/{int1}/{int2}/{limit}/{str1}/{str2}`

    exemple http://localhost:8080/api/list/3/5/10/a/b
  - GET `/api/stats`
    
    exemple http://localhost:8080/api/stats

Remarques:
- j'ai utilisé un projet php sans framework pour faire les API avec un minimum de config
- j'ai fait les stats via redis mais j'aurais pu les faire via une implémentation de base de données (plus simple et rapide avec redis)


Merci !