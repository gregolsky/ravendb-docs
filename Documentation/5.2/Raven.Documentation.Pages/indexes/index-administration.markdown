# Index Administration
---

{NOTE: }

* Indexes can be easily managed from the [Studio](../studio/database/indexes/indexes-list-view#indexes-list-view)
  or via [Maintenance operations](../client-api/operations/what-are-operations#maintenance-operations) in the Client API.

* In this article:
  * [Index management operations](../indexes/index-administration#index-management-operations)
      * [Pause & resume index](../indexes/index-administration#pause--resume-index)
      * [Pause & resume indexing](../indexes/index-administration#pause--resume-indexing)
      * [Disable & enable index](../indexes/index-administration#disable--enable-index)
      * [Disable & enable indexing](../indexes/index-administration#disable--enable-indexing)
      * [Reset index](../indexes/index-administration#reset-index)
      * [Delete index](../indexes/index-administration#delete-index)
      * [Set index lock mode](../indexes/index-administration#set-index-lock-mode)
      * [Set index priority](../indexes/index-administration#set-index-priority)
  * [Index states](../indexes/index-administration#index-states)
  * [Customize indexing configuration](../indexes/index-administration#customize-indexing-configuration)

{NOTE/}

---

{PANEL: Index management operations}

{CONTENT-FRAME: }

##### Pause & resume index
---

* An index can be paused (and resumed).
 
* See [pause index](../client-api/operations/maintenance/indexes/stop-index) & [resume index](../client-api/operations/maintenance/indexes/start-index) for detailed information.
 
* Operation scope: Single node.

{CONTENT-FRAME/}
{CONTENT-FRAME: }

##### Pause & resume indexing
---

* You can pause (and resume) indexing of ALL indexes.
 
* See [pause indexing](../client-api/operations/maintenance/indexes/stop-indexing) & [resume indexing](../client-api/operations/maintenance/indexes/start-indexing) for detailed information.
 
* Operation scope: Single node.

{CONTENT-FRAME/}
{CONTENT-FRAME: }

##### Disable & enable index
---

* An index can be disabled (and enabled), this is a persistent operation.
 
* See [disable index](../client-api/operations/maintenance/indexes/disable-index) & [enable index](../client-api/operations/maintenance/indexes/enable-index) for detailed information.
 
* Operation scope: Single node, or all database-group nodes.

{CONTENT-FRAME/}
{CONTENT-FRAME: }

##### Disable & enable indexing
---

* Indexing can be disabled (and enabled) for ALL indexes, this is a persistent operation.
 
* This is done from the [database list view](../studio/database/databases-list-view#more-actions) in the Studio.
 
* Operation scope: All database-group nodes.

{CONTENT-FRAME/}
{CONTENT-FRAME: }

##### Reset index
---

* Resetting an index will force re-indexing of all documents that match the index definition.  
  An index usually needs to be reset once it reached its error quota and is in an _Error_ state.
  An index usually needs to be reset when it reaches its error quota and enters the _Error_ state.
 
* See [reset index](../client-api/operations/maintenance/indexes/reset-index) for detailed information.
 
* Operation scope: Single node.

{CONTENT-FRAME/}
{CONTENT-FRAME: }

##### Delete index
---

* An index can be deleted from the database.
 
* See [delete index](../client-api/operations/maintenance/indexes/delete-index) for detailed information.
 
* Operation scope: All database-group nodes.

{CONTENT-FRAME/}
{CONTENT-FRAME: }

##### Set index lock mode
---

* The lock mode controls whether modifications to the index definition are applied (static indexes only).
 
* See [set index lock](../client-api/operations/maintenance/indexes/set-index-lock) for detailed information.
 
* Operation scope: All database-group nodes.

{CONTENT-FRAME/}
{CONTENT-FRAME: }

##### Set index priority
---

* Each index has a dedicated thread that handles all the work for the index.  
  Setting the index priority will affect the thread priority at the operating system level.
 
* See [set index priority](../client-api/operations/maintenance/indexes/set-index-priority) for detailed information.
 
* Operation scope: All database-group nodes.

{CONTENT-FRAME/}
{PANEL/}

{PANEL: Index states}

An index can be in one of the following states:

* `Normal`
    * The index is active, any new data is indexed.

* `Paused`
    * New data is not being indexed.
    * Queries will be stale as new data is not indexed.
    * The indexing process will resume upon any of the following actions:
        * Setting _'Resume indexing'_ from the [Studio](../studio/database/indexes/indexes-list-view#indexes-list-view---actions).
        * Resume indexing from the Client API. See [Resume index operation](../client-api/operations/maintenance/indexes/start-index).
        * Restarting the server.
        * Reloading the database. See [How to reload the database](../studio/database/settings/database-settings#how-to-reload-the-database).

* `Disabled`
    * New data is not being indexed.
    * Queries will be stale as new data is not indexed.
    * The indexing process will resume upon either of the following:
        * Setting _'Enable indexing'_ from the [Studio](../studio/database/indexes/indexes-list-view#indexes-list-view---actions).  
        * Resume indexing from the Client API. See [Enable index operation](../client-api/operations/maintenance/indexes/enable-index).
    * The index will NOT automatically resume upon restarting the server or reloading the database.

* `Idle` (auto-indexes only)  

    * An auto-index is marked as _'Idle'_ when it has not been queried for a configurable period of time.  
      This state indicates that the index may later be deleted, as detailed in the following points:

    * Specifically, an **auto-index** is marked as _'Idle'_ when the time difference between its last-query-time
      and the most recent time the database was queried (using any other index) exceeds a configurable threshold. This threshold is set by the
      [Indexing.TimeToWaitBeforeMarkingAutoIndexAsIdleInMin](../server/configuration/indexing-configuration#indexing.timetowaitbeforemarkingautoindexasidleinmin) configuration key (30 minutes by default).

    * This mechanism is designed to prevent auto-indexes from being marked as idle in databases that were offline for a long period,
      had no new data to index, were not queried, or were recently restored from a snapshot or backup.

    * While an auto-index is _'Idle'_, it is NOT considered _'Disabled'_.  
      **It continues to index data** from any documents relevant to its definition as they are created or modified.

    * An idle auto-index returns to the _'Normal'_ state in the following cases:
        * When it is queried again.
        * When the auto-index is reset.
        * When [the database is reloaded](../studio/database/settings/database-settings#how-to-reload-the-database).

    * If the idle auto-index is Not returned to the _'Normal'_ state, the server will **delete** it after a configurable time period,  
      set by the [Indexing.TimeToWaitBeforeDeletingAutoIndexMarkedAsIdleInHrs](../server/configuration/indexing-configuration#indexing.timetowaitbeforedeletingautoindexmarkedasidleinhrs) configuration key (72 hours by default).

    * Note:  
      The server evaluates whether an auto-index should be marked as idle, or whether an idle auto-index should be deleted,  
      at intervals defined by the [Indexing.CleanupIntervalInMin](../server/configuration/indexing-configuration#indexing.cleanupintervalinmin) configuration key (10 minutes by default).  
      If _TimeToWaitBeforeMarkingAutoIndexAsIdleInMin_ or _TimeToWaitBeforeDeletingAutoIndexMarkedAsIdleInHrs_  
      are set to values smaller than the cleanup interval, the index will be marked as idle or deleted only after the cleanup interval elapses.

* `Error`
    * An indexing error can occur when the indexing-function is malformed (e.g., incorrectly written)  
      or when the document data is corrupted/missing.
    * Once the index error rate exceeds a certain threshold (as described in [Marking index as errored](../indexes/troubleshooting/debugging-index-errors#marking-index-as-errored)),  
      the index state is marked as _'Error'_. 
    * An errored index cannot be queried - all queries against it will result in an exception.
    * Learn more in [Debugging index errors](../indexes/troubleshooting/debugging-index-errors).

* `Faulty`
    * When an index is successfully defined but the server fails to open its index data file from disk, or if this file is corrupted,  
      the server marks the index as _'Faulty'_, indicating that something is wrong with its index data files.
    * Learn more in [Faulty index](../studio/database/indexes/indexes-list-view#indexes-list-view---errors).

{PANEL/}

{PANEL: Customize indexing configuration}

* There are many [indexing configuration](../server/configuration/indexing-configuration) options available.  

* A configuration key with a **"per-index" scope** can be customized for a specific index,  
  overriding the server-wide and the database configuration values.

* The "per-index" configuration key can be set from:
  * The [configuration tab](../studio/database/indexes/create-map-index#configuration) in the Edit Index view in the Studio.  
  * The [index class constructor](../indexes/creating-and-deploying#creating-an-index-with-custom-configuration) when defining an index.  
  * The [index definition](../client-api/operations/maintenance/indexes/put-indexes#put-indexes-operation-with-indexdefinition) when sending a [putIndexesOperation](../client-api/operations/maintenance/indexes/put-indexes).

**Expert configuration options**:

* [Server.IndexingAffinityMask](../server/configuration/server-configuration#server.indexingaffinitymask) - Control the affinity mask of indexing threads
* [Server.NumberOfUnusedCoresByIndexes](../server/configuration/server-configuration#server.numberofunusedcoresbyindexes) - Set the number of cores that _won't_ be used by indexes

{PANEL/}

## Related Articles

### Indexes

- [Indexes: Overview](../studio/database/indexes/indexes-overview#indexes-overview)
- [What are Indexes](../indexes/what-are-indexes)

### Troubleshooting

- [Debugging Index Errors](../indexes/troubleshooting/debugging-index-errors)
