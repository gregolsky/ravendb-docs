# Counters and Other Features
---

{NOTE: }

* This section describes the relationships between Counters and other RavenDB features:  
   * How Counters are supported by the different features.  
   * How Counters trigger features' execution.  

* In this page:  
  * [Counters and Indexing](../../document-extensions/counters/counters-and-other-features#counters-and-indexing)  
  * [Counters and Queries](../../document-extensions/counters/counters-and-other-features#counters-and-queries)  
  * [Counters and Revisions](../../document-extensions/counters/counters-and-other-features#counters-and-revisions)  
  * [Counters and Changes API](../../document-extensions/counters/counters-and-other-features#counters-and-changes-api)  
  * [Counters and Ongoing Tasks](../../document-extensions/counters/counters-and-other-features#counters-and-ongoing-tasks) - `Backup`, `External replication`, `ETL`
  * [Counters and Other Features: summary](../../document-extensions/counters/counters-and-other-features#counters-and-other-features-summary)  
  * [Including Counters](../../document-extensions/counters/counters-and-other-features#including-counters)  
{NOTE/}

---

{PANEL: }

### Counters and Indexing  

Indexing Counters can speed-up finding them and the documents that contain them.  

* **Indexing Counter Values**
    Dynamic indexes (aka auto-indexes) _cannot_ index counter values. To index counter values, 
    create a static index that inherits from `AbstractCountersIndexCreationTask` ([see here](../../document-extensions/counters/indexing)).

* **Indexing Counter Names**  
    Re-indexing due to Counter-name modification is normally rare enough to pause no performance issues.  
    To index a document's Counters by name, use [CounterNamesFor](../../document-extensions/counters/indexing#section-1).  

---

###Counters and Queries  

Send the server **raw queries** for execution.  

* You can query Counters **by name** but **not by value**.  
  This is because queries are generally [based on indexes](../../start/getting-started#example-iii---querying), and Counter values are [not indexed](../../document-extensions/counters/counters-and-other-features#counters-and-indexing).  
* Counter values **can** be [projected](../../indexes/querying/projections) from query results, as demonstrated in the following examples.  
  This way a client can get counter values from a query without downloading whole documents.  

* Use [RawQuery](../../client-api/session/querying/how-to-query#session.advanced.rawquery) to send the server raw RQL expressions for execution.  
   * You can use the `counter` method.  
     **Returned Counter Value**: **Accumulated**

  * You can use the `counterRaw` method.  
     **Returned Counter Value**: **Distributed**  
     A Counter's value is returned as a series of values, each maintained by one of the nodes.  
      * It is not expected of you to use this in your application.  
        Applications normally use the Counter's overall value, and very rarely refer to the value each node gives it.  
      
        
    `counter` and `counterRaw` samples:  
    {CODE-TABS}
    {CODE-TAB:php:counter counters_region_rawqueries_counter@DocumentExtensions\Counters\Counters.php /}
    {CODE-TAB:php:counterRaw counters_region_rawqueries_counterRaw@DocumentExtensions\Counters\Counters.php /}
    {CODE-TABS/}

---

###Counters and Revisions  

A document revision stores all the document Counters' names and values when the revision was created.  
 
* **Stored Counter Values**: **Accumulated**  
  A revision stores a Counter's value as a single sum, with no specification of the Counter's value on each node.  

* Revisions-creation can be initiated by **Counter-name modification**.  
   * When the Revisions feature is enabled, the creation or deletion of a Counter initiates the creation of a new document revision.  
   * Counter **value** modifications do **not** cause the creation of new revisions.  

---

###Counters and Changes API

[Changes API](../../client-api/changes/what-is-changes-api#changes-api) is a Push service, that can inform you of various changes on the Server, including [changes in Counter values](../../client-api/changes/how-to-subscribe-to-counter-changes#changes-api--how-to-subscribe-to-counter-changes).  
You can target all Counters, or specify the ones you wish to follow.  

* **Pushed Counter Value**: **Accumulated**  
  `Changes API` methods return a Counter's value as a single sum, without specifying its value on each node.  
* The service is initiated by **Counter Value Modification**.  

---

###Counters and Ongoing Tasks:

Each [ongoing task](../../studio/database/tasks/ongoing-tasks/general-info) relates to Counters in its own way.  

* **Counters** and the **Backup task**  
    There are two [backup](../../studio/database/tasks/backup-task) types: **logical-backup** and **snapshot**.  
    Both types store and restore **all** data, including Counters.  
    Both types operate as an ongoing backup routine, with a pre-set time schedule.  
    * Logical Backup:  
      **Backed-up Counter values**: **Distributed**  
      A logical backup is a higher-level implementation of Smuggler.  
      As with Smuggler, Counters are backed-up and restored including their values on all nodes.  
    * Snapshot:  
      A snapshot stores all data and settings as a single binary image.
      All components, including Counters, are restored to the exact same state they've been at during backup.  

* **Counters** and the **External Replication task**  
    The ongoing [external replication](../../studio/database/tasks/ongoing-tasks/external-replication-task) task replicates all data, including Counters.  
    * **Replicated Counter Value**: **Distributed**  
        Counters are replicated along with their values on all nodes.  
    * Replication can be initiated by both **Counter-name update** _and_ **Counter-value modification**.  

* **Counters** and the **ETL task**  
    [ETL](../../server/ongoing-tasks/etl/basics) is used to export data from RavenDB to an external (either Raven or SQL) database.  
    * [SQL ETL](../../server/ongoing-tasks/etl/sql) is **not supported**.  
      Counters cannot be exported to an SQL database over SQL ETL.  
    * [RavenDB ETL](../../server/ongoing-tasks/etl/raven) **is supported**.  
      Counters [are](../../server/ongoing-tasks/etl/raven#counters) exported over RavenDB ETL.  
      * Export can be initiated by both **Counter-name update** _and_ **Counter-value modification**.  
      * **Exported Counter Value**: **Distributed**  
        Counters are exported along with their values on all nodes.  
      * Counters can be [exported using a script](../../server/ongoing-tasks/etl/raven#adding-counter-explicitly-in-a-script).  
        **Default behavior**: When an ETL script is not provided, Counters are exported.  
      
{NOTE: }
###Counters and Other Features: Summary

Use this table to find if and how various RavenDB features are triggered by Counters, 
and how the various features handle Counter values.  

* In the **Triggered By** column:  
    * _Document Change_ - Feature is triggered by a Counter Name update.  
    * _Countrer Value Modification_ - Feature is triggered by a Counter Value modification.  
    * _Time Schedule_ - Feature is invoked by a pre-set time routine.  
    * _No Trigger_ - Feature is executed manually, through the Studio or by a Client.  
* In the **Counter Value** column:  
    * _Accumulated_ - Counter Value is handled as a single accumulated sum.  
    * _Distributed_ - Counter Value is handled as a series of values maintained by cluster nodes.  

| **Feature** | **Triggered by** | **Counter Value** |
|-------------|:-------------|:-------------|
| [Indexing](../../document-extensions/counters/counters-and-other-features#counters-and-indexing) | _Document Change_ | doesn't handle values |
| [LINQ Queries](../../document-extensions/counters/counters-and-other-features#counters-and-queries) | _No trigger_ | _Accumulated_ |
| [Raw Queries](../../document-extensions/counters/counters-and-other-features#counters-and-queries) | _No trigger_ | `counter()` - _Accumulated_ <br> `counterRaw()` - _Distributed_ |
| [Backup Task](../../document-extensions/counters/counters-and-other-features#counters-and-ongoing-tasks) | _Time Schedule_ | _Distributed_ |
| [RavenDB ETL Task](../../document-extensions/counters/counters-and-other-features#counters-and-ongoing-tasks) | _Document Change_, <br> _Countrer Value Modification_ | _Distributed_ |
| [External Replication task](../../document-extensions/counters/counters-and-other-features#counters-and-ongoing-tasks) | _Document Change_, <br> _Countrer Value Modification_ | _Distributed_ |
| [Changes API](../../document-extensions/counters/counters-and-other-features#counters-and-changes-api) | _Countrer Value Modification_ | _Accumulated_ |
| [Revision creation](../../document-extensions/counters/counters-and-other-features#counters-and-revisions) | _Document Change_ | _Accumulated_ |

{NOTE/}

---

###Including Counters  
You can [include](../../client-api/how-to/handle-document-relationships#includes) Counters while loading a document.  
An included Counter is retrieved in the same request as its owner-document and is held by the session, 
so it can be immediately retrieved when needed with no additional remote calls.


* **Including Counters when using [session.load](../../client-api/session/loading-entities#session--loading-entities)**:  
    * Include a single Counter using `include_counter`  
    * Include multiple Counters using `include_counters`  

    `include_counter` and `include_counters` usage samples:  
    {CODE-TABS}
    {CODE-TAB:php:IncludeCounter counters_region_load_include1@DocumentExtensions\Counters\Counters.php /}
    {CODE-TAB:php:IncludeCounters counters_region_load_include2@DocumentExtensions\Counters\Counters.php /}
    {CODE-TABS/}

* **Including Counters when using [Session.Query](../../client-api/session/querying/how-to-query#session--querying--how-to-query)**:  
    * Include a single Counter using `include_counter`.  
    * Include multiple Counters using `include_counters`.  

    `include_counter` and `include_counters` usage samples:  
    {CODE-TABS}
    {CODE-TAB:php:IncludeCounter counters_region_query_include_single_Counter@DocumentExtensions\Counters\Counters.php /}
    {CODE-TAB:php:IncludeCounters counters_region_query_include_multiple_Counters@DocumentExtensions\Counters\Counters.php /}
    {CODE-TABS/}

{PANEL/}

## Related articles
**Studio Articles**:  
[Studio Counters Management](../../studio/database/document-extensions/counters#counters)  

**Client-API - Session Articles**:  
[Counters Overview](../../document-extensions/counters/overview)  
[Creating and Modifying Counters](../../document-extensions/counters/create-or-modify)  
[Deleting a Counter](../../document-extensions/counters/delete)  
[Retrieving Counter Values](../../document-extensions/counters/retrieve-counter-values)  
[Counters In Clusters](../../document-extensions/counters/counters-in-clusters)  

**Client-API - Operations Articles**:  
[Counters Operations](../../client-api/operations/counters/get-counters#operations--counters--how-to-get-counters)  
