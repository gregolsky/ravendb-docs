﻿# General Guide - Containers & RavenDB

## Introduction

RavenDB is a NoSQL database built for performance, simplicity, and ease of use. It integrates seamlessly with containerized environments, enabling stable and efficient deployments. While running our database in a containerized environment offers numerous benefits, it also comes with challenges, such as managing data persistence and ensuring secure networking.

This guide provides a comprehensive overview of running RavenDB in containers. It summarizes key concepts, outlines requirements, and highlights the advantages of containerized setups. Additionally, it serves as a hub of knowledge, linking to detailed documentation, guides, and articles to help you navigate every aspect of deploying RavenDB in containerized environments.

## Contents

1. [Core Concepts & Difficulties](#core-concepts)
2. [What We Offer](#what-we-offer)
3. [What We Require](#what-we-require)
4. [Benefits](#benefits)

---

## 1. Core concepts

#### Containers
Containers encapsulate RavenDB and its dependencies for consistent behavior across environments. A containerized setup bundles the runtime, libraries, and configurations necessary for RavenDB operations into a single isolated unit. This isolation ensures that RavenDB functions reliably regardless of variations in the host operating system or underlying hardware.

While virtual machines also provide isolation, they achieve it by replicating entire operating systems, which introduces significant overhead. Containers, by contrast, leverage the shared kernel architecture, using the host OS kernel to create isolated environments without duplicating the operating system. This approach makes containers inherently lightweight, efficient, and scalable. Other secondary concepts, like container storage and networking, enable this primary design, enhancing practicality while maintaining performance advantages over traditional VMs.

#### Orchestration
Most systems depend on multiple applications and technologies that must work together effectively to serve the end user. Container technology simplifies this by allowing multiple applications to run in isolated environments (containers), but manual deployment and management of all applications separately can become a challenge.

The orchestration simplifies the deployment and maintenance of systems built using multiple containers - it combines all containerized apps into a preconfigured and hardened definition. This definition describes the ideal state of the system, including application configurations, storage, networking, security measures, and scalability. This way is called the declarative approach. The developer describes the system (usually by writing a .yaml file) and supplies the orchestrator with it. Orchestrator acknowledges the definition and starts working - it deploys the described system and manages it to keep it working exactly like that.

Orchestrators also simplify cluster scaling by design and enable self-healing by automatically recovering application containers across available nodes (instances or machines).


{PANEL: Difficulties}

Hosting a database in containerized environment brings many difficulties and challenges, that developers need to face.

#### Statefulness in a Stateless World
Containers are inherently stateless and designed to be ephemeral, but RavenDB, as a database, requires durable storage for its data.  

This dichotomy introduces challenges like data persistence—storage backends, such as AWS EBS, Azure Disk, or on-premise NFS, which must be properly configured or integrated with the orchestration platform.

#### Security & Networking
Proper network setup is necessary for secure and reliable communication between RavenDB nodes since RavenDB defines a Cluster differently.
Each Node is a fully independent entity rather than just a "replica."
This design involves a couple of quirks that need addressing.  

This independence enhances resiliency but requires solid configuration to maintain consistent and secure communication across the cluster.

#### Orchestrator Complexity
Orchestration platforms simplify container management but can complicate troubleshooting.
The network setup can obscure communication paths, making identifying issues like latency or misconfigurations difficult.
Containerized RavenDB instances may be challenging to analyze without direct access due to security limitations on Docker images.

This security detail restricts traditional debugging tools and complicates problem resolution.
It sometimes requires the usage of container host tooling, which can be not sufficient or even available in serverless regime.
Effective management of RavenDB in such environments requires a solid understanding of the database and the orchestration platform.
{PANEL/}


## 2. What We Offer

In the matter of deploying containers, aside from Server features, we explicitly offer

#### Official Docker Images
Official RavenDB images for:

- Ubuntu & Windows Nanoserver -  [Dockerhub](https://hub.docker.com/r/ravendb/ravendb/)  
- Security Hardened RedHat UBI - [IronBank](https://repo1.dso.mil/dsop/opensource/ravendb/ravendb)

#### Helm Chart of Secured RavenDB Cluster
Automatic RavenDB cluster deployment in Kubernetes.
 
- [ArtifactHub](https://artifacthub.io/packages/helm/ravendb-cluster/ravendb-cluster)
- [GitHub](https://github.com/ravendb/helm-charts)

#### Deployment Articles & Guides
Step-by-step guides for containerized and orchestrated setups - [View Deployment Guides](./deployment-guides) or [Visit Articles Page](https://ravendb.net/articles)

#### Containers Knowledge Base
Detailed documentation of hosting RavenDB in container environments - [Containers Documentation](.)

#### Technical Support
Professional & community support scoped at deploying RavenDB in containers - [Support Page](https://ravendb.net/support)

---

## 3. What We Require

#### Container Runtime
Docker, Podman, containerd or an equivalent.

####  Compute
Sufficient memory & CPU. Either on-premise or cloud solutions. See [Containers > Requirements > Compute](./requirements/compute).

#### Networking Configuration
Proper communication between nodes and ingress management. See article [Containers > Requirements > Networking](./requirements/networking).

#### Persistent Storage
Configure volumes to retain database data across container restarts. See the article [Containers > Requirements > Storage](./requirements/storage).

#### Security
Depending on your solution, you'll need SSL/TLS certificates, role-based access control (RBAC), or other methods for secure deployment. See the article [Containers > Requirements > Security](./requirements/security).

## 4. Benefits
### a. Containers
#### Consistency
Containers ensure a uniform environment across development, staging, and production, eliminating the "it works on my machine" problem.

#### Isolation
Containers provide an isolated environment for the application. Thus, there's no need to plan strategy of environment sharing between applications.

#### Lightweight
Containers share the host's kernel, reducing overhead, improving resource efficiency, and keeping processes separate.


### b. Orchestration
#### Declarative Management
Tools like Kubernetes enable you to define the desired system state through YAML files, such as application configuration, nodes, resource allocation, security, and networking. The orchestrator ensures the system maintains this state automatically.

#### High Availability
Orchestration platforms distribute RavenDB nodes across multiple machines or regions, ensuring resilience against hardware failures. Automatic failover mechanisms keep your database accessible even when a node goes offline.
