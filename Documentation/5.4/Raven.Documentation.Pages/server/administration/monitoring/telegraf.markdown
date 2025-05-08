﻿# Monitoring: Telegraf Plugin

---

{NOTE: }

* The endpoints listed in this page provide a wide variety of performance metrics for a RavenDB 
  instance, including, for example, information regarding indexing, communication inside a cluster, 
  and the server's memory usage.  

* These metrics can be collected using the [RavenDB Telegraf Plugin](https://docs.influxdata.com/telegraf/v1.18/plugins/#ravendb) 
  and displayed as live graphs using [Grafana](https://grafana.com/).  

* In this page:  
   * [Telegraf](../../../server/administration/monitoring/telegraf#telegraf)  
   * [Monitoring Endpoints](../../../server/administration/monitoring/telegraf#monitoring-endpoints)  
   * [JSON Fields Returned by the Endpoints](../../../server/administration/monitoring/telegraf#json-fields-returned-by-the-endpoints)  

{NOTE/}

---

{PANEL: Telegraf}

[Telegraf](https://www.influxdata.com/time-series-platform/telegraf/) is a popular data collection 
and processing agent designed to work with time series data. Version 1.18 of Telegraf has a new 
plugin for RavenDB that collects data from RavenDB's monitoring endpoints. The recommended use 
for the RavenDB plugin is to have Telegraf output to [InfluxDB](https://www.influxdata.com/products/influxdb/), 
and from there the data can be queried by [Grafana](https://grafana.com/) and displayed on your own 
data tracking dashboard. But this feature is flexible - Telegraf can output data to other destinations.  

## Monitoring Endpoints

The monitoring endpoints output data in JSON format. There are four endpoints:  

* `<your server URL>/admin/monitoring/v1/server`  
* `<your server URL>/admin/monitoring/v1/databases`  
* `<your server URL>/admin/monitoring/v1/indexes`  
* `<your server URL>/admin/monitoring/v1/collections`  

## JSON Fields Returned by the Endpoints

The following is a list of JSON fields returned by the endpoints:  

| Endpoint Suffix | Field Name | Description |
| - | - | - |
| `collections` | `collection_name` | Collection name |
| `collections` | `database_name` | Name of this collection's database |
| `collections` | `documents_count` | Number of documents in collection |
| `collections` | `documents_size_in_bytes` | Size of documents in bytes |
| `collections` | `revisions_size_in_bytes` | Size of revisions in bytes |
| `collections` | `tombstones_size_in_bytes` | Size of tombstones in bytes |
| `collections` | `total_size_in_bytes` | Total size of collection in bytes |
| `databases` | `database_id` | Database ID |
| `databases` | `database_name` | Database name |
| `databases` | `counts_alerts` | Number of alerts |
| `databases` | `counts_attachments` | Number of attachments |
| `databases` | `counts_documents` | Number of documents |
| `databases` | `counts_performance_hints` | Number of performance hints |
| `databases` | `counts_rehabs` | Number of rehabs |
| `databases` | `counts_revisions` | Number of revision documents |
| `databases` | `counts_unique_attachments` | Number of unique attachments |
| `databases` | `indexes_auto_count` | Number of auto indexes |
| `databases` | `indexes_count` | Number of indexes |
| `databases` | `indexes_disabled_count` | Number of disabled indexes |
| `databases` | `indexes_errored_count` | Number of error indexes |
| `databases` | `indexes_errors_count` | Number of indexing errors |
| `databases` | `indexes_idle_count` | Number of idle indexes |
| `databases` | `indexes_stale_count` | Number of stale indexes |
| `databases` | `indexes_static_count` | Number of static indexes |
| `databases` | `statistics_doc_puts_per_sec` | Number of document puts per second (one minute rate) |
| `databases` | `statistics_map_index_indexes_per_sec` | Number of indexed documents per second for map indexes (one minute rate) |
| `databases` | `statistics_map_reduce_index_mapped_per_sec` | Number of maps per second for map-reduce indexes (one minute rate) |
| `databases` | `statistics_map_reduce_index_reduced_per_sec` | Number of reduces per second for map-reduce indexes (one minute rate) |
| `databases` | `statistics_request_average_duration_in_ms` | Average request time in milliseconds |
| `databases` | `statistics_requests_count` | Number of requests from database start |
| `databases` | `statistics_requests_per_sec` | Number of requests per second (one minute rate) |
| `databases` | `storage_documents_allocated_data_file_in_mb` | Documents storage allocated size in MB |
| `databases` | `storage_documents_used_data_file_in_mb` | Documents storage used size in MB |
| `databases` | `storage_indexes_allocated_data_file_in_mb` | Index storage allocated size in MB |
| `databases` | `storage_indexes_used_data_file_in_mb` | Index storage used size in MB |
| `databases` | `storage_total_allocated_storage_file_in_mb` | Total storage size in MB |
| `databases` | `storage_total_free_space_in_mb` | Remaining storage disk space in MB |
| `databases` | `storage_io_read_operations` | Storage I/O Read operations<br>Optional, Linux only |
| `databases` | `storage_io_write_operations` | Storage I/O Write operations<br>Optional, Linux only |
| `databases` | `storage_read_throughput_in_kb` | Storage Read throughput in KB<br>Optional, Linux only |
| `databases` | `storage_write_throughput_in_kb` | Storage Write throughput in KB<br>Optional, Linux only |
| `databases` | `storage_queue_length` | Storage queue length<br>Optional, Linux only |
| `databases` | `time_since_last_backup_in_sec` | LastBackup |
| `databases` | `uptime_in_sec` | Database up-time |
| `indexes` | `entries_count` | Number of entries in the index |
| `indexes` | `errors` | Number of index errors |
| `indexes` | `index_name` | Index name |
| `indexes` | `is_invalid` | Indicates if index is invalid |
| `indexes` | `lagtime` | Indexing Lag Time |
| `indexes` | `lock_mode` | Index lock mode |
| `indexes` | `mapped_per_sec` | Number of maps per second (one minute rate) |
| `indexes` | `priority` | Index priority |
| `indexes` | `reduced_per_sec` | Number of reduces per second (one minute rate) |
| `indexes` | `state` | Index state |
| `indexes` | `status` | Index status |
| `indexes` | `time_since_last_indexing_in_sec` | Time since last indexing |
| `indexes` | `time_since_last_query_in_sec` | Time since last query |
| `indexes` | `type` | Index type |
| `server` | `backup_current_number_of_running_backups` | Number of backups currently running |
| `server` | `backup_max_number_of_concurrent_backups` | Max number of backups that can run concurrently |
| `server` | `certificate_server_certificate_expiration_left_in_sec` | Server certificate expiration left |
| `server` | `certificate_well_known_admin_certificates` | List of well known admin certificate thumbprints |
| `server` | `cluster_current_term` | Cluster term |
| `server` | `cluster_id` | Cluster ID |
| `server` | `cluster_index` | Cluster index |
| `server` | `cluster_node_state` | Current node state |
| `server` | `node_tag` | Current node tag |
| `server` | `config_server_urls` | Server URLs |
| `server` | `public_server_url` | The server's public URL |
| `server` | `config_tcp_server_urls` | Server TCP URL |
| `server` | `config_public_tcp_server_urls` | Server public TCP URL |
| `server` | `cpu_assigned_processor_count` | Number of assigned processors on the machine |
| `server` | `cpu_machine_io_wait` | IO wait in % |
| `server` | `cpu_machine_usage` | Machine CPU usage in % |
| `server` | `cpu_process_usage` | Process CPU usage in % |
| `server` | `cpu_processor_count` | Number of processor on the machine |
| `server` | `cpu_thread_pool_available_worker_threads` | Number of available worker threads in the thread pool |
| `server` | `cpu_thread_pool_available_completion_port_threads` | Number of available completion port threads in the thread pool |
| `server` | `databases_loaded_count` | Number of loaded databases |
| `server` | `databases_total_count` | Number of all databases |
| `server` | `disk_remaining_storage_space_percentage` | Remaining server storage disk space in % |
| `server` | `disk_system_store_total_data_file_size_in_mb` | Server storage total size in MB |
| `server` | `disk_system_store_used_data_file_size_in_mb` | Server storage used size in MB |
| `server` | `disk_total_free_space_in_mb` | Remaining server storage disk space in MB |
| `server` | `license_expiration_left_in_sec` | Server license expiration left |
| `server` | `license_max_cores` | Server license max CPU cores |
| `server` | `license_type` | Server license type |
| `server` | `license_utilized_cpu_cores` | Server license utilized CPU cores |
| `server` | `memory_allocated_in_mb` | Server allocated memory in MB |
| `server` | `memory_installed_in_mb` | InstalledMemory |
| `server` | `memory_low_memory_severity` | Server low memory flag value |
| `server` | `memory_physical_in_mb` | PhysicalMemory |
| `server` | `memory_total_dirty_in_mb` | Dirty memory that is used by the scratch buffers in MB |
| `server` | `memory_total_swap_size_in_mb` | Server total swap size in MB |
| `server` | `memory_total_swap_usage_in_mb` | Server total swap usage in MB |
| `server` | `memory_working_set_swap_usage_in_mb` | Server working set swap usage in MB |
| `server` | `network_concurrent_requests_count` | Number of concurrent requests |
| `server` | `network_last_authorized_non_cluster_admin_request_time_in_sec` | Server last authorized non cluster admin request time |
| `server` | `network_last_request_time_in_sec` | Server last request time |
| `server` | `network_requests_per_sec` | Number of requests per second (one minute rate) |
| `server` | `network_tcp_active_connections` | Number of active TCP connections |
| `server` | `network_total_requests` | Total number of requests since server startup |
| `server` | `server_full_version` | Server full version |
| `server` | `server_process_id` | Server process ID |
| `server` | `server_version` | Server version |
| `server` | `uptime_in_sec` | Server up-time |

{PANEL/}

## Related Articles

### Monitoring
- [Prometheus](../../../server/administration/monitoring/prometheus)  

### Administration
- [SNMP Administration](../../../server/administration/SNMP/snmp)  
- [Zabbix](../../../server/administration/SNMP/setup-zabbix)  

### Integrations
- [PostgreSQL Overview](../../../integrations/postgresql-protocol/overview)  
- [Power BI](../../../integrations/postgresql-protocol/power-bi)  
