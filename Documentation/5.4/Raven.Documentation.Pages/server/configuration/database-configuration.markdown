# Configuration: Database
---

{NOTE: }

* The following configuration keys control database behavior.

* In this article:
    * Server-wide scope:  
      [Databases.Compression.CompressAllCollectionsDefault](../../server/configuration/database-configuration#databases.compression.compressallcollectionsdefault)  
      [Databases.Compression.CompressRevisionsDefault](../../server/configuration/database-configuration#databases.compression.compressrevisionsdefault)  
      [Databases.ConcurrentLoadTimeoutInSec](../../server/configuration/database-configuration#databases.concurrentloadtimeoutinsec)  
      [Databases.FrequencyToCheckForIdleInSec](../../server/configuration/database-configuration#databases.frequencytocheckforidleinsec)  
      [Databases.MaxConcurrentLoads](../../server/configuration/database-configuration#databases.maxconcurrentloads)

    * Server-wide, or database scope:  
      [Databases.CollectionOperationTimeoutInSec](../../server/configuration/database-configuration#databases.collectionoperationtimeoutinsec)  
      [Databases.DeepCleanupThresholdInMin](../../server/configuration/database-configuration#databases.deepcleanupthresholdinmin)  
      [Databases.MaxIdleTimeInSec](../../server/configuration/database-configuration#databases.maxidletimeinsec)  
      [Databases.OperationTimeoutInSec](../../server/configuration/database-configuration#databases.operationtimeoutinsec)  
      [Databases.PulseReadTransactionLimitInMb](../../server/configuration/database-configuration#databases.pulsereadtransactionlimitinmb)  
      [Databases.QueryOperationTimeoutInSec](../../server/configuration/database-configuration#databases.queryoperationtimeoutinsec)  
      [Databases.QueryTimeoutInSec](../../server/configuration/database-configuration#databases.querytimeoutinsec)  
      [Databases.RegularCleanupThresholdInMin](../../server/configuration/database-configuration#databases.regularcleanupthresholdinmin)

{NOTE/}

---

{PANEL:Databases.Compression.CompressAllCollectionsDefault}

Set whether [documents compression](../../server/storage/documents-compression) is enabled by default for ALL COLLECTIONS in newly created databases.  
Setting this to _false_ does not prevent you from enabling compression later, after a database is created.

- **Type**: `bool`
- **Default**: `false`
- **Scope**: Server-wide only

{PANEL/}

{PANEL:Databases.Compression.CompressRevisionsDefault}

Set whether [documents compression](../../server/storage/documents-compression) is enabled by default for REVISIONS in newly created databases.  
It may be useful to disable this option if the database is expected to run on very low-end hardware.  
Setting this to _false_ does not prevent you from enabling compression later, after a database is created.

- **Type**: `bool`
- **Default**: `true`
- **Scope**: Server-wide only

{PANEL/}

{PANEL:Databases.ConcurrentLoadTimeoutInSec}

The time (in seconds) to wait for a database to start loading (and become available) when the system is under load - when many different databases are being loaded concurrently.

- **Type**: `int`
- **Default**: `60`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Databases.FrequencyToCheckForIdleInSec}

The interval (in seconds) at which the system checks for idle databases.

- **Type**: `int`
- **Default**: `60`
- **Scope**: Server-wide only

{PANEL/}

{PANEL:Databases.MaxConcurrentLoads}

Specifies the maximum number of databases that can be loaded concurrently.

- **Type**: `int`
- **Default**: `8`
- **Scope**: Server-wide only

{PANEL/}

{PANEL:Databases.CollectionOperationTimeoutInSec}

The time (in seconds) to wait before canceling certain collection operations (e.g., batch delete documents).  
If the operation exceeds the specified duration, an *OperationCanceledException* is thrown.

- **Type**: `int`
- **Default**: `300`
- **Scope**: Server-wide or per database

{PANEL/}

{PANEL:Databases.DeepCleanupThresholdInMin}

EXPERT ONLY.  
A deep database cleanup will be performed when this number of minutes has passed since the last time work was done on the database.

- **Type**: `int`
- **Default**: `5`
- **Scope**: Server-wide or per database

{PANEL/}

{PANEL:Databases.MaxIdleTimeInSec}

Sets the maximum idle time (in seconds) for a database.  
After this period, an idle database will be unloaded from memory.  
Consider using a lower value if memory resources are limited.

- **Type**: `int`
- **Default**: `900`
- **Scope**: Server-wide or per database

{PANEL/}

{PANEL:Databases.OperationTimeoutInSec}

The time (in seconds) to wait before canceling certain operations, such as indexing terms.

- **Type**: `int`
- **Default**: `300`
- **Scope**: Server-wide or per database

{PANEL/}

{PANEL:Databases.PulseReadTransactionLimitInMb}

The number of megabytes used by encryption buffers (for encrypted databases) or 32-bit mapped buffers (on 32-bit systems), 
after which a read transaction is renewed to reduce memory usage during long-running operations such as backups or streaming.

- **Type**: `int`
- **Default**: The default value is determined by the total physical memory (RAM) available on the machine:  
    * On 32-bit platforms, or when less than 1 GB of RAM is available: `16 MB`  
    * Up to 4 GB RAM: `32 MB`  
    * Up to 16 GB RAM: `64 MB`  
    * Up to 64 GB RAM: `128 MB`  
    * More than 64 GB RAM: `256 MB`  
- **Scope**: Server-wide or per database  

{PANEL/}

{PANEL:Databases.QueryOperationTimeoutInSec}

The time (in seconds) to wait before canceling a query-related operation (e.g., patch or delete query).  
The timeout resets with each processed document,  
and will only be exceeded if no document is processed within the specified period.

- **Type**: `int`
- **Default**: `300`
- **Scope**: Server-wide or per database

{PANEL/}

{PANEL:Databases.QueryTimeoutInSec}

The time (in seconds) to wait before canceling a query.  
Applies to both regular and streamed queries.

If the query exceeds the specified time, an *OperationCanceledException* is thrown.  
For streamed queries, the timeout is reset each time a result is pushed to the stream.  
The timeout will be exceeded only if no result is streamed within the timeout period.

- **Type**: `int`
- **Default**: `300`
- **Scope**: Server-wide or per database

{PANEL/}

{PANEL:Databases.RegularCleanupThresholdInMin}

EXPERT ONLY.  
A regular database cleanup will be performed when this number of minutes has passed since the database was last idle.

- **Type**: `int`
- **Default**: `10`
- **Scope**: Server-wide or per database

{PANEL/}
