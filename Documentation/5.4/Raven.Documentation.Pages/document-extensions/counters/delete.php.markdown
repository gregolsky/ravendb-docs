# Delete Counter  
---

{NOTE: }

* Use the `countersFor.delete` method to remove a specific Counter from a document.

* All the document's Counters are deleted when the document itself is deleted.  

* For all other `countersFor` methods see this [Overview](../../document-extensions/counters/overview#counter-methods-and-the--object).

* In this page:
    * [`delete` usage](../../document-extensions/counters/delete#delete-usage)
    * [Example](../../document-extensions/counters/delete#example)
    * [Syntax](../../document-extensions/counters/delete#syntax)

{NOTE/}

---

{PANEL: `delete` usage}

**Flow**:  

* Open a session.  
* Create an instance of `countersFor`.  
    * Either pass `countersFor` an explicit document ID, -or-  
    * Pass it an [entity tracked by the session](../../client-api/session/loading-entities), 
      e.g. a document object returned from [session.query](../../client-api/session/querying/how-to-query) or from [session.load](../../client-api/session/loading-entities#load).  
* Call `documentCounters.delete`.
* Call `session.saveChanges` for the changes to take effect.  

**Note**:

* A Counter you deleted will be removed only after the execution of `saveChanges()`.  
* `delete` will **not** generate an error if the Counter doesn't exist.
* Deleting a document deletes all its Counters as well.

{PANEL/}

{PANEL: Syntax}

{CODE:php Delete-definition@DocumentExtensions\Counters\Counters.php /}

| Parameter     | Type   | Description    |
|---------------|--------|----------------|
| **counter** | `string` | Counter name ([see example](../../document-extensions/counters/overview#managing-counters)) |

{PANEL/}

## Related articles

**Studio Articles**:  
[Studio Counters Management](../../studio/database/document-extensions/counters#counters)  

**Client-API - Session Articles**:  
[Counters Overview](../../document-extensions/counters/overview)  
[Creating and Modifying Counters](../../document-extensions/counters/create-or-modify)  
[Retrieving Counter Values](../../document-extensions/counters/retrieve-counter-values)  
[Counters and other features](../../document-extensions/counters/counters-and-other-features)  
[Counters In Clusters](../../document-extensions/counters/counters-in-clusters)  

**Client-API - Operations Articles**:  
[Counters Operations](../../client-api/operations/counters/get-counters#operations--counters--how-to-get-counters)  
