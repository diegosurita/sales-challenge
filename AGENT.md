# General Guidelines

- All commands (JavaScript or PHP) should be executed in the docker compose service `larave.test`.

# Backend Architecture and Code Style Guidelines

## PHP

- All PHP code should follow strictly the PSR-12 coding standard. Use Context7 tool for docs and code style checks.
- All params and return types should be type hinted.
- When using a method or function with more than 2 params, always use named parameters.

## Modular Design

- The application is following the modular monolith architecture.
- Each module should be organized in a separate folder under `src/`.
- Communication between modules should be done via a contract in the `src/Shared/Core/Contracts` folder. This contract should be an interface that defines the methods that the module exposes to other modules via a well-defined API. 
- Modules that need to consume the API of another module should implement the contract defined in the `src/Shared/Core/Contracts` folder. This way, we can ensure that the modules are decoupled and can be easily replaced or modified without affecting other modules.
- Backend tests in `tests/` should be organized in a similar way to the `src/` folder, with a separate folder for each module.

## Clean Architecture Principles

Follow the principles of Clean Architecture, including the guidelines bellow.

### Frameworks and Drivers Layer

**General Guidelines:**

- Files related to this layer should be placed in folder `src/{Module}/Infrastructure`.
- This layer should contain code related to frameworks, databases, external services and modules communication.

**Folder Structure:**

- `Persistence`: This folder should contain code related to database interactions, such as repositories, models and migrations.
- `Security`: This folder should contain code related to authentication and authorization, such as guards, policies and permissions.
- `Gateways`: This folder should contain code related to communication with external services, such as API clients, message brokers and module communication.

### Interface Adapters Layer

**General Guidelines:**

- Files related to this layer should be placed in folder `src/{Module}/Interface`.
- This layer should contain code related to controllers, presenters, views and other interface adapters.

**Folder Structure:**

- `Controllers`: This folder should contain code related to handling HTTP requests and responses.
- `APIs`: This folder should contain code related to API for other modules communication.

### Application & Enterprise Layer

**General Guidelines:**

- Files related to this layer should be placed in folder `src/{Module}/Core`.
- This layer should contain code related to the application's business logic and domain entities.
- This layer should not have any dependencies on the Frameworks and Drivers Layer or the Interface Adapters Layer.
- Use case classes should always have a method `execute` that will be called by the controllers to execute the use case. This method can return a value or not, depending on the use case.

**Folder Structure:**

- `UseCases`: This folder should contain code related to the application's use cases, which are the specific actions that the application can perform.
- `Entities`: This folder should contain code related to the application's domain entities, which are the core business objects that the application manipulates.
- `Contracts`: This folder should contain the interfaces that define the contracts for the implementations outside the Application and Enterprise layers, following Clean Architecture principles.
- `Exceptions`: This folder should contain code related to custom exceptions that can be thrown by the classes in the Application and Enterprise layers.
- `DTOs`: This folder should contain Data Transfer Objects, which are used to transfer data between layers and modules.

# Frontend Architecture and Code Style Guidelines

- The frontend is following Laravel's default architecture.

## Components Organization Guidelines

- Reusable components should be placed in the `resources/js/components` folder following the modular design principles.
- Each module should have its own folder inside `resources/js/components` where the components related to that module should be placed. For example, components related to the Client module should be placed in `resources/js/components/client`.
- Components that are shared between modules should be placed in `resources/js/components/shared`.
- Pages should be placed in the `resources/js/pages` folder, following the modular design principles. Each page should be organized in a separate file, and if necessary, a folder with related components can be created inside the `resources/js/components` folder.
- Tests for frontend components should be placed in the same folder as the component they are testing, with the same name as the component file but with a `.spec.ts` extension. For example, tests for `resources/js/components/client/ClientList.vue` should be placed in `resources/js/components/client/ClientList.spec.ts`.
- SVG icons should be placed in the `resources/js/icons` folder. Each icon should be organized in a separate file, and if necessary, a folder with related icons can be created inside the `resources/js/icons` folder.

## Code Style Guidelines

- Always use the linter and formatter configured in the project after making changes to Vue and TypeScript files.