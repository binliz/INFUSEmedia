# Run project

Modify .env file to change permissions and url
Run

```bash
docker-comopse up -d --build
```

Rebuild

```bash
docker-comopse down -v
docker-comopse up -d --build
```

Open database:

```bash
docker-compose exec db mysql -uroot -p{find in env password} {find in env database name}
```

List

```sql
    select *
    from logs;
```

Time to make this project:

1. Create infrastructure 1 hour
2. Make code 2 hours



