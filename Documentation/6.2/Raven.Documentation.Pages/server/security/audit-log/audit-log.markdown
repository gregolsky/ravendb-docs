# Audit Log
---

{NOTE: }

* [Authorization](../../../server/security/authorization/security-clearance-and-permissions) controls who can access RavenDB and what operations they can perform.

* In addition, RavenDB provides an optional **Audit Log** to track who connects to the system and when.  
  Audit logs are available only when using a secure server.

* Audit entries are recorded for operations at the database level.  
  See the full list of what is being logged below.

* In this page:
  * [Enabling the audit log](../../../server/security/audit-log/audit-log#enabling-the-audit-log)
  * [What is being logged](../../../server/security/audit-log/audit-log#what-is-being-logged)
  * [Things to consider](../../../server/security/audit-log/audit-log#things-to-consider)

{NOTE/}

---

{PANEL: Enabling the audit log}

* **To enable writing to the audit log**, set the following configuration key: 
  * [Security.AuditLog.FolderPath](../../../server/configuration/security-configuration#security.auditlog.folderpath) - set the path to a folder where RavenDB will store the audit logs.

* In addition, the following configurations are available:
  * [Security.AuditLog.RetentionTimeInHours](../../../server/configuration/security-configuration#security.auditlog.retentiontimeinhourssecurity.auditlog.retentiontimeinhrs) - set the number of hours audit logs are kept before they are deleted.
  * [Security.AuditLog.RetentionSizeInMb](../../../server/configuration/security-configuration#security.auditlog.retentionsizeinmb) - The maximum total size of audit log files, after which older files will be deleted.
  * [Security.AuditLog.Compress](../../../server/configuration/security-configuration#security.auditlog.compress) - determine whether to compress the audit log files.
  * [Logs.MaxFileSizeInMb](../../../server/configuration/logs-configuration#logs.maxfilesizeinmb) - a new log file is created when this limit is reached (or daily). 

* Learn how to set configuration keys in this [configuration overview](../../../server/configuration/configuration-options).

{PANEL/}

{PANEL: What is being logged}

* Once the audit log is enabled, the following action items will be logged:  
  * **Connecting to RavenDB**:  
    Every time a connection is made to RavenDB  
    Every time a connection to RavenDB is closed  
    When a connection is rejected by RavenDB as invalid  
    Adding a certificate + what privileges it was granted  
    Deleting a certificate  
    Opening a 2FA session  
    Failing to open a 2FA session  
  * **Cluster**:  
    Adding a node to the cluster  
    Removing a node from the cluster  
  * **Database**:  
    Creating or deleting a database  
    Modifying the database topology  
    Modifying the database record  
    Exporting or importing a database  
  * **Indexes**:  
    Creating an index  
    Deleting an index  
    Resetting index  
  * **Analyzers and sorters**:  
    Adding or deleting an analyzer  
    Adding or deleting a sorter  
  * **Admin script**:  
    Executing an admin JS script  
  * **Integrations**:  
    Setting or deleting a user from PostgreSQL protocol credentials.
  * **Connection strings**:  
    Adding or deleting a connection string  
  * **Queries**:  
    Deleting documents via patching  
    Streaming query results from @all_docs  
  * **Revisions**:  
    Deleting revisions  
    Modifying revisions settings  
    Modifying revisions bin cleaner settings  
  * **Ongoing tasks**:  
    Adding or updating an ETL task  
    Adding or updating a Kafka Sink or a RabbitMQ Sink task  
    Adding or updating External Replication task   
    Adding or updating Replication Hub or a Replication Sink task  
    Deleting any ongoing task   
    Toggling ongoing task state  
  * **Backups**:  
    Adding a manual (one time) backup task  
    Adding, updating, or deleting a periodic backup task  
    Delaying the backup operation  

{PANEL/}

{PANEL: Things to consider}

* **Audit log processing**:  
  RavenDB only writes to the audit logs without any additional processing.  
  The audit entries can be loaded into centralized audit and analysis systems using dedicated tools.

* **Audit logs are local**:  
  It is important to note that the audit logs are local.  
  For instance, if a database resides on node **C** and is removed by a command issued from node **B**,
  the corresponding audit entry will be recorded in the audit log of node **B**, not in that of node **C**.

* **Connection logging**:  
  RavenDB records connections in the audit log, not individual requests.
  Logging contains the time of the TCP connection, the certificate being used, and the level of access granted to that certificate at the time of the connection.
  This is done for performance and manageability; otherwise, the audit logs would become excessively large and difficult to manage.  
  With HTTP 1.1, a single TCP connection is utilized for multiple requests.  
  If you require more detailed logs at the level of individual HTTP requests, you can use a proxy in front of RavenDB to log the appropriate requests as they are made.

{PANEL/}

## Related articles

### Security

- [Overview](../../../server/security/overview)
- [Authorization](../../../server/security/authorization/security-clearance-and-permissions)
- [Security Configuration](../../../server/configuration/security-configuration)
- [Common Errors and FAQ](../../../server/security/common-errors-and-faq)
