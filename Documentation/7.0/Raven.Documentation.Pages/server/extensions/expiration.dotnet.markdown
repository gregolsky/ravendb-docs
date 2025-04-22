﻿# Document Expiration
---

{NOTE: }

* Documents can be given a future expiration time in which they'll be automatically deleted.  
* The Expiration feature deletes documents set for expiration, when their time has passed.  
* You can enable or disable the expiration feature while the database is already live with data.  

* In this page:  
  * [Expiration feature usages](../../server/extensions/expiration#expiration-feature-usages)  
  * [Configuring the expiration feature](../../server/extensions/expiration#configuring-the-expiration-feature)  
     * [Configure expiration settings using the client API](../../server/extensions/expiration#configure-expiration-settings-using-the-client-api)  
  * [Setting the document expiration time](../../server/extensions/expiration#setting-the-document-expiration-time)  
  * [Eventual consistency considerations](../../server/extensions/expiration#eventual-consistency-considerations)  
 {NOTE/}

---

{PANEL: Expiration feature usages}

Use the Expiration feature when data is needed only for a given time period.  
E.g., for -  

 * Shopping cart data that is kept only for a certain time period  
 * Email links that need to be expired after a few hours  
 * A web application login session details  
 * Cache data from an SQL server  

{PANEL/}

{PANEL: Configuring the expiration feature}

Documents expiration settings can be changed via Studio or the API.  
It is possible to:  

* Enable or Disable the deletion of expired documents.  
  Default value: **Disable** the deletion of expired documents.  
* Determine how often RavenDB would look for expired documents and delete them.  
  Default value: **60 seconds**  
* Set the maximal number of documents that RavenDB is allowed to delete per interval.  
  Default value: **All expired documents**  

---

{INFO: }
[Learn how to configure expiration settings via Studio](../../studio/database/settings/document-expiration)
{INFO/}

---

### Configure expiration settings using the client API  

Modify the expiration settings using the client API by setting an `ExpirationConfiguration` 
object and sending it to RavenDB using a `ConfigureExpirationOperation` operation.  

#### Example:

{CODE configuration@Server\Expiration\Expiration.cs /}

#### `ExpirationConfiguration`

{CODE expirationConfiguration@Server\Expiration\Expiration.cs /}

| Parameter | Type | Description |
| - | - | - |
| **Disabled** | `bool` | If `true`, deleting expired documents is disabled for the entire database.<BR>Default: `true` |
| **DeleteFrequencyInSec** | `long?` | Determines how often (in seconds) the expiration feature looks for expired documents and deletes them.<BR>Default: `60` |
| **MaxItemsToProcess** | `long?` | Determines the maximal number of documents the feature is allowed to delete in one run. |

{PANEL/}

{PANEL: Setting the document expiration time}

* To set a document expiration time, add the document's `@metadata` an 
  `@expires` property with the designated expiration time as a value.  
  Set the time in **UTC** format, not local time. E.g. -  
  **"@expires": "2025-04-22T08:00:00.0000000Z"**  
  {WARNING: }
  Metadata properties starting with `@` are for internal RavenDB usage only.  
  Do _not_ use the metadata `@expires` property for any other purpose than 
  scheduling a document's expiration time for the built-in expiration feature.  
  {WARNING/}
* If and when the expiration feature is enabled, it will process all documents 
  carrying the `@expires` flag and automatically delete each document 
  [by its expiration time](../../server/extensions/expiration#eventual-consistency-considerations).  
* To set the document expiration time from the client, use the following code:
  {CODE expiration1@Server\Expiration\Expiration.cs /}

{PANEL/}

{PANEL: Eventual consistency considerations}

* Internally, RavenDB tracks all documents carrying the `@expires` flag even if the 
  expiration feature is disabled. This way, once the expiration feature is enabled expired 
  documents can be processed without delay.  
* Once a document expires, it may take up to the _delete frequency interval_ (60 seconds by default) 
  until is it actually deleted.  
* Deletion may be further delayed if `MaxItemsToProcess` is set, limiting the number 
  of documents that RavenDB is allowed to delete each time the expiration feature is invoked.  
* Expired documents are _not_ filtered out during `load`, `query`, or indexing, so be aware that 
  as long as an expired document hasn't been actually deleted it may still be included in the results.  

{PANEL/}

## Related Articles

### Studio

- [Setting Document Expiration in Studio](../../studio/database/settings/document-expiration)
