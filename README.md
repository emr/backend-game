# Backend Game
This is the implementation of "Backend Game" project.

## Installation
If you wish to change default configuration, copy `.env.dev` as `.env.dev.local` and edit the file.

Projects run on port 80 by default, if you need to change this, edit
[docker-compose.yml](https://github.com/emr/masomo-backend-game/blob/main/docker-compose.yml)

Run docker containers:
```
docker-compose up
```

## API Documentation
You can access detailed api documentation at http://localhost/api/v1.
This page uses Swagger UI to visualize api specification defined in
[api_specification.yaml](https://github.com/emr/masomo-backend-game/blob/main/api_specification.yaml)
