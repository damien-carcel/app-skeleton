.PHONY: pull-back-end
pull-back-end:
	cd $(CURDIR)/back && docker-compose pull --ignore-pull-failures

.PHONY: pull-front-end
pull-front-end:
	cd $(CURDIR)/front && docker-compose pull --ignore-pull-failures

.PHONY: build-back-end-dev
build-back-end-dev: pull-back-end
	cd $(CURDIR)/back && DOCKER_BUILDKIT=1 docker build --pull . --tag carcel/skeleton/php:7.3 --target dev

.PHONY: build-front-end-dev
build-front-end-dev: pull-front-end

.PHONY: build-back-end
build-back-end: build-back-end-dev

.PHONY: build-front-end
build-front-end: build-front-end-dev

.PHONY: build
build: build-front-end build-back-end
