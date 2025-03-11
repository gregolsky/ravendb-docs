﻿# Get Revisions Operation

---

{NOTE: }

* Use `GetRevisionsOperation` to GET the document's revisions.

* To only COUNT the number of revisions without getting them, use the [get_count_for](../../../../document-extensions/revisions/client-api/session/counting) session method.

* In this page:  
  * [Get all revisions](../../../../document-extensions/revisions/client-api/operations/get-revisions#get-all-revisions)  
  * [Paging results](../../../../document-extensions/revisions/client-api/operations/get-revisions#paging-results)  
  * [Syntax](../../../../document-extensions/revisions/client-api/operations/get-revisions#syntax)  

{NOTE/}

---

{PANEL: Get all revisions}

{CODE:php getAllRevisions@DocumentExtensions\Revisions\ClientAPI\Operations\GetRevisions.php /}

{PANEL/}

{PANEL: Paging results}

* Get and process revisions, one page at a time:
  {CODE:php getRevisionsWithPaging@DocumentExtensions\Revisions\ClientAPI\Operations\GetRevisions.php /}

* The document ID, start & page size can be wrapped in a `Parameters` object:
  {CODE:php getRevisionsWithPagingParams@DocumentExtensions\Revisions\ClientAPI\Operations\GetRevisions.php /}

{PANEL/}

{PANEL: Syntax}

| Parameter | Type | Description |
| - | - | - |
| **id** | `string` | ID of the document to get revisions for |
| **start** | `int` | Revision number to start from |
| **pageSize** | `int` | Number of revisions to get |
| **parameters** | `Parameters` | An object that wraps `id`, `start`, and `pageSize` |

{PANEL/}

## Related Articles

### Document Extensions

* [Document Revisions Overview](../../../../document-extensions/revisions/overview)  
* [Revert Revisions](../../../../document-extensions/revisions/revert-revisions)  
* [Revisions and Other Features](../../../../document-extensions/revisions/revisions-and-other-features)  

### Client API

* [Revisions: API Overview](../../../../document-extensions/revisions/client-api/overview)  
* [Operations: Configuring Revisions](../../../../document-extensions/revisions/client-api/operations/configure-revisions)  
* [Session: Loading Revisions](../../../../document-extensions/revisions/client-api/session/loading)  
* [Session: Including Revisions](../../../../document-extensions/revisions/client-api/session/including)  
* [Session: Counting Revisions](../../../../document-extensions/revisions/client-api/session/counting)  

### Studio
* [Settings: Document Revisions](../../../../studio/database/settings/document-revisions)  
* [Document Extensions: Revisions](../../../../studio/database/document-extensions/revisions)  
