# Symfony NextJs Api Platform

![Symfony NextJs](https://i.ibb.co/k8gPytC/new-project.png)

## Project Information

This project is designed as a decoupled application, showcasing the separation of concerns between the **Frontend** and **Backend** components.

### Backend
```bash
localhost:8800
```

The backend is developed using **Symfony** with **Api Platform**, following a hexagonal architecture that utilizes bounded contexts. This design allows for better organization and separation of different aspects of the application. The backend implements the **CQRS** (Command Query Responsibility Segregation) pattern, utilizing both command and query buses for efficient management of information. This approach facilitates smooth registration, modification, and access to data within the system.

### Frontend
```bash
localhost:4000
```

The frontend operates independently from the backend and is built using **Next.js** with **React**. It leverages **TypeScript** for improved type safety and developer experience. For styling and layout, the project integrates **Tailwind CSS**, allowing for a responsive and modern design without compromising performance. The decoupling of the frontend from the backend enables independent development, deployment, and scaling of both components.



## Installation

To install this project, follow these steps:

1. Clone the repository:
   ```bash
   git clone https://github.com/venturaproject/symfony-api-platform.git
   cd symfony-api-platform
   make run


## Architecture
   ```
❯ api/src
├── Product
│   ├── Application
│   │   ├── Command
│   │   │   ├── CreateProduct
│   │   │   │   ├── CreateProductCommand.php
│   │   │   │   └── CreateProductCommandHandler.php
│   │   │   ├── DeleteProduct
│   │   │   │   ├── DeleteProductCommand.php
│   │   │   │   └── DeleteProductCommandHandler.php
│   │   │   └── UpdateProduct
│   │   │       ├── UpdateProductCommand.php
│   │   │       └── UpdateProductCommandHandler.php
│   │   ├── DTO
│   │   │   └── ProductDTO.php
│   │   ├── EventListener
│   │   │   └── ProductCreatedListener.php
│   │   ├── Exception
│   │   │   └── ProductNotFoundException.php
│   │   └── Query
│   │       ├── GetAll
│   │       │   ├── GetAllProductsQuery.php
│   │       │   └── GetAllProductsQueryHandler.php
│   │       └── GetById
│   │           ├── GetIdProductQuery.php
│   │           └── GetIdProductQueryHandler.php
│   ├── Domain
│   │   ├── Entity
│   │   │   └── Product.php
│   │   ├── Event
│   │   │   └── ProductCreatedEvent.php
│   │   ├── Repository
│   │   │   └── ProductRepositoryInterface.php
│   │   └── Service
│   │       ├── DeleteProductService.php
│   │       └── UpdateProductService.php
│   └── Infrastructure
│       ├── ApiPlatform
│       │   ├── Processor
│       │   │   ├── CreateProductProcessor.php
│       │   │   ├── DeleteProductProcessor.php
│       │   │   └── UpdateProductProcessor.php
│       │   ├── Provider
│       │   │   ├── ProductByIdProvider.php
│       │   │   └── ProductsProvider.php
│       │   └── Resource
│       │       └── ProductResource.php
│       ├── Mapping
│       │   └── Doctrine
│       │       └── Product.orm.xml
│       ├── Repository
│       │   └── ProductRepository.php
│       └── ValidationDTO
│           ├── CreateProductInputDTO.php
│           └── UpdateProductDTO.php
├── Shared
│   ├── Application
│   │   ├── Command
│   │   │   ├── CommandBusInterface.php
│   │   │   ├── CommandHandlerInterface.php
│   │   │   └── CommandInterface.php
│   │   ├── Event
│   │   │   └── EventHandlerInterface.php
│   │   ├── Query
│   │   │   ├── QueryBusInterface.php
│   │   │   ├── QueryHandlerInterface.php
│   │   │   └── QueryInterface.php
│   │   └── Service
│   │       ├── EmailService.php
│   │       └── EventPublisher.php
│   ├── Domain
│   │   ├── Aggregate
│   │   │   └── AggregateRoot.php
│   │   ├── Event
│   │   │   ├── DomainEventBusInterface.php
│   │   │   └── EventInterface.php
│   │   ├── Exception
│   │   │   ├── DomainExeption.php
│   │   │   ├── ForbidenException.php
│   │   │   └── NotFoundException.php
│   │   ├── Security
│   │   │   ├── AuthUserInterface.php
│   │   │   ├── CurrentUserProviderInterface.php
│   │   │   └── UserPasswordHasherInterface.php
│   │   ├── Type
│   │   │   └── DomainCollection.php
│   │   ├── UuidGenerator
│   │   │   └── UuidGeneratorInterface.php
│   │   └── ValueObject
│   │       └── Uuid.php
│   └── Infrastructure
│       ├── Bus
│       │   ├── CommandBus.php
│       │   ├── DomainEventBus.php
│       │   └── QueryBus.php
│       ├── Controller
│       │   └── Web
│       │       └── HomepageController.php
│       ├── DTO
│       │   └── Pagination
│       │       └── PaginationDTO.php
│       ├── DataFixtures
│       │   ├── AppFixtures.php
│       │   └── ProductFixtures.php
│       ├── EventListener
│       │   ├── ApiExceptionListener.php
│       │   └── ExceptionListener.php
│       ├── Kernel.php
│       ├── Persistence
│       │   └── Doctrine
│       │       └── Types
│       │           └── UuidValueObjectType.php
│       ├── Security
│       │   ├── CurrentUserProvider.php
│       │   └── UserPasswordHasher.php
│       ├── Subscriber
│       │   └── DomainExceptionSubscriber.php
│       └── UuidGenerator
│           └── RamseyUuidGenerator.php
└── User
    ├── Application
    │   ├── Command
    │   │   ├── CreateUser
    │   │   │   ├── CreateUserCommand.php
    │   │   │   └── CreateUserCommandHandler.php
    │   │   ├── ChangePassword
    │   │   │   ├── ChangePasswordCommand.php
    │   │   │   └── ChangePasswordCommandHandler.php
    │   │   ├── ChangeUsername
    │   │   │   ├── ChangeUsernameCommand.php
    │   │   │   └── ChangeUsernameCommandHandler.php
    │   │   └── DeleteUser
    │   │       ├── DeleteUserCommand.php
    │   │       └── DeleteUserCommandHandler.php
    │   ├── Console
    │   │   └── CreateUserConsole.php
    │   ├── DTO
    │   │   ├── UpdatePasswordDTO.php
    │   │   ├── UpdateUsernameDTO.php
    │   │   └── UserDTO.php
    │   └── Query
    │       ├── GetCurrentUser
    │       │   ├── GetCurrentUserQuery.php
    │       │   └── GetCurrentUserQueryHandler.php
    │       └── GetUserById
    │           ├── GetUserByIdQuery.php
    │           └── GetUserByIdQueryHandler.php
    ├── Domain
    │   ├── Entity
    │   │   └── User.php
    │   ├── Event
    │   │   └── UserDeletedEvent.php
    │   ├── Exception
    │   │   └── UserNotFoundException.php
    │   ├── Factory
    │   │   └── UserFactory.php
    │   ├── Repository
    │   │   └── UserRepositoryInterface.php
    │   └── Service
    │       ├── DeleteUserService.php
    │       ├── UserPasswordService.php
    │       └── UserProfileService.php
    └── Infrastructure
        ├── ApiPlatform
        │   ├── Processor
        │   │   ├── CreateUserProcessor.php
        │   │   └── UpdateUserPasswordProcessor.php
        │   ├── Provider
        │   │   └── UserByIdProvider.php
        │   └── Resource
        │       ├── UserPasswordResource.php
        │       └── UserResource.php
        ├── Mapping
        │   ├── Doctrine
        │   │   └── User.orm.xml
        │   └── Serializer
        │       └── User.xml
        ├── Repository
        │   └── UserRepository.php
        └── ValidationDTO
            └── CreateUserInputDTO.php