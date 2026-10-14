# AWS Production Deployment Plan - Jajal Medical Case Management

> [!NOTE]
> **Plan Designation**: This document serves as a comprehensive **AWS Production Deployment Plan & Architectural Specification**. The local application has been built and verified on local development infrastructure; production provisioning will follow the cloud architecture below.

---

## 1. Architectural Topology Overview

```
                          [ Internet Traffic ]
                                   │
                                   ▼ (HTTPS : 443)
                      [ AWS Application Load Balancer ]
                         (ACM Managed SSL Certificate)
                                   │
                    ┌──────────────┴──────────────┐
                    ▼                             ▼
       [ EC2 Instance: App Node 1 ]   [ EC2 Instance: App Node 2 ]
       - Ubuntu 24.04 LTS             - Ubuntu 24.04 LTS
       - Nginx + PHP 8.2-FPM          - Nginx + PHP 8.2-FPM
       - Laravel Application          - Laravel Application
                    │                             │
                    └──────────────┬──────────────┘
                                   │ (MySQL : 3306 - Private Subnet)
                                   ▼
                    [ AWS RDS Multi-AZ MySQL 8.0 ]
                    - Encrypted at rest (AWS KMS)
                    - Automated daily snapshots & backup
```

---

## 2. Infrastructure Components

### A. Compute: Amazon EC2
- **Instance Sizing**: `t4g.small` or `t3.medium` instances running Ubuntu 24.04 LTS with PHP 8.2-FPM and Nginx.
- **Process Supervisor**: `systemd` or `supervisor` for Laravel queue workers and background jobs.
- **OPcache & Optimization**: `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`.

### B. Managed Database: Amazon RDS for MySQL 8.0
- **Engine**: MySQL 8.0 Community Edition in a dedicated **Private VPC Subnet** (no public IP assigned).
- **High Availability**: Multi-AZ deployment for zero-downtime automated failover.
- **Security**: Encrypted storage volumes via AWS KMS (`aws/rds`).

### C. Storage: Amazon S3
- **Object Storage**: S3 bucket with **Block Public Access** enabled.
- **Access Control**: IAM Instance Profile with least-privilege IAM policy allowing EC2 to put and get medical attachments.
- **Encryption**: Server-Side Encryption with KMS (SSE-KMS).

---

## 3. Security Groups and Network Isolation

| Security Group | Inbound Rules | Outbound Rules | Purpose |
|---|---|---|---|
| **`sg-alb` (Load Balancer)** | • Port 80 (HTTP from `0.0.0.0/0` - redirects to 443)<br>• Port 443 (HTTPS from `0.0.0.0/0`) | Port 80 to `sg-app` | Public internet entry point |
| **`sg-app` (EC2 Instances)** | • Port 80 from `sg-alb` only<br>• Port 22 from bastion/VPN IP only | All traffic out (NAT Gateway) | Application execution |
| **`sg-db` (RDS MySQL)** | • Port 3306 from `sg-app` only | None | Private database protection |

---

## 4. Secrets Management & Environment Configuration

- **Zero Plaintext Secrets**: Application secrets (`APP_KEY`, `DB_PASSWORD`, API secrets) must **never** be checked into Git.
- **AWS Secrets Manager / SSM Parameter Store**:
  - Securely store database credentials, mail secrets, and encryption keys.
  - Injected during CI/CD deployment via AWS CodeDeploy or user-data script directly into `.env` with file permissions `600` owned by `www-data`.
- **Environment Isolation**:
  - `APP_ENV=production`
  - `APP_DEBUG=false`

---

## 5. SSL / TLS & Production Hardening

- **SSL Certificates**: Free, automatically renewing SSL certificates managed by **AWS Certificate Manager (ACM)** attached to the ALB.
- **Nginx Security Headers**:
  - `X-Frame-Options "SAMEORIGIN"`
  - `X-Content-Type-Options "nosniff"`
  - `Strict-Transport-Security "max-age=31536000; includeSubDomains"`
  - `Content-Security-Policy "default-src 'self' ..."`
- **Automated Deployment Pipeline**: GitHub Actions running automated PHPUnit tests, building assets, and triggering AWS CodeDeploy or rolling EC2 updates upon successful branch merges.