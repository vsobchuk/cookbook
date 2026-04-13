# Cookbook Demo App
This project is built for education purposes and to prove skills of testing.
You may find some tests are redundant, but it was required by the task

## Local Deployment
- Run next command to prepare environment files:
```shell
make docker-env
```
- (optional) Change .env files configurations `./app/.env` or `./docker-environment/.env`
- Start containers: `make up`
- Stop containers: `make down`

To bring up the Alpine nvm may be used

- install Node version: 24 `nvm install 24`
- install dependencies: `npm install`
- build the bundle: `npm run dev`