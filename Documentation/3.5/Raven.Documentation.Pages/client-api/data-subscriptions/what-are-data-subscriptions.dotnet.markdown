﻿#What are Data Subscriptions?

Data subscriptions provide a reliable and handy way to retrieve documents from the database for processing purposes by application jobs.

To start a job with this feature you need to create a subscription in the database along with a set of conditions with which a document has to comply in order to be sent through subscription channel i.e. collection name, key prefix or property filtering.

When you open the subscription it will send you all documents matching the specified criteria. Documents are sent in batches (its size is configurable along with connection options) and once you process them (within the specified time) the whole batch is marked as processed. Documents are always sent in Etag order which means that documents which have already been processed won't be sent again over this subscription.

This also ensures that you never miss any document even in the presence of failure - subscription will retry to send documents from the last acknowledged and processed document (by tracking its Etag).

Every time you open the subscription you receive all new or changed documents since the last time you pulled. After you download and process all documents you can still keep the subscription open to get new or modified documents. Under the hood, the data subscription uses [Changes API](../changes/what-is-changes-api) to be notified about any document changes, so it is able to immediately provide subscribers with new documents that match the subscription criteria.

The subscriptions are persistent and long lived objects. A subscription created by one client can be accessed by another client by opening it by using a unique subscription identifier (however, there can be only one client connected at any time), so you are expected to hold on to that id and make use of it. You can find more details about managing and consuming data subscriptions in further articles.

##Accessing Data Subscriptions
Data subscriptions are accessible by a document store. You can take advantage of synchronous or asynchronous data subscription API methods. They are respectively exposed by the following properties:

{CODE accessing_subscriptions@ClientApi\DataSubscriptions\DataSubscriptions.cs /}
