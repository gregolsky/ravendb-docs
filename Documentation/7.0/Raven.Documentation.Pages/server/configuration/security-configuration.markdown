# Configuration: Security
---

{NOTE: }

* The following configuration keys allow you to control the desired level of security in a RavenDB server.  
  To learn more about RavenDB's security features, see this [security overview](../../server/security/overview).

* In this page:
  * Security.AuditLog:  
    [Security.AuditLog.EnableArchiveFileCompression](../../server/configuration/security-configuration#security.auditlog.enablearchivefilecompression)  
    [Security.AuditLog.FolderPath](../../server/configuration/security-configuration#security.auditlog.folderpath)  
    [Security.AuditLog.ArchiveAboveSizeInMb](../../server/configuration/security-configuration#security.auditlog.archiveabovesizeinmb)  
    [Security.AuditLog.MaxArchiveDays](../../server/configuration/security-configuration#security.auditlog.maxarchivedays)
  * Security.Certificate:  
    [Security.Certificate.Change.Exec](../../server/configuration/security-configuration#security.certificate.change.exec)  
    [Security.Certificate.Change.Exec.Arguments](../../server/configuration/security-configuration#security.certificate.change.exec.arguments)  
    [Security.Certificate.Exec](../../server/configuration/security-configuration#security.certificate.exec)  
    [Security.Certificate.Exec.TimeoutInSec](../../server/configuration/security-configuration#security.certificate.exec.timeoutinsec)  
    [Security.Certificate.ExpiringThresholdInDays](../../server/configuration/security-configuration#security.certificate.expiringthresholdindays)  
    [Security.Certificate.LetsEncrypt.Email](../../server/configuration/security-configuration#security.certificate.letsencrypt.email)  
    [Security.Certificate.Load.Exec](../../server/configuration/security-configuration#security.certificate.load.exec)  
    [Security.Certificate.Load.Exec.Arguments](../../server/configuration/security-configuration#security.certificate.load.exec.arguments)  
    [Security.Certificate.Password](../../server/configuration/security-configuration#security.certificate.password)  
    [Security.Certificate.Path](../../server/configuration/security-configuration#security.certificate.path)  
    [Security.Certificate.Renew.Exec](../../server/configuration/security-configuration#security.certificate.renew.exec)  
    [Security.Certificate.Renew.Exec.Arguments](../../server/configuration/security-configuration#security.certificate.renew.exec.arguments)  
    [Security.Certificate.Validation.Exec](../../server/configuration/security-configuration#security.certificate.validation.exec)  
    [Security.Certificate.Validation.Exec.Arguments](../../server/configuration/security-configuration#security.certificate.validation.exec.arguments)  
    [Security.Certificate.Validation.Exec.TimeoutInSec](../../server/configuration/security-configuration#security.certificate.validation.exec.timeoutinsec)  
    [Security.Certificate.Validation.KeyUsages](../../server/configuration/security-configuration#security.certificate.validation.keyusages)
  * Security.Csrf:  
    [Security.Csrf.AdditionalOriginHeaders](../../server/configuration/security-configuration#security.csrf.additionaloriginheaders)  
    [Security.Csrf.Enabled](../../server/configuration/security-configuration#security.csrf.enabled)  
    [Security.Csrf.TrustedOrigins](../../server/configuration/security-configuration#security.csrf.trustedorigins)
  * Security.MasterKey:  
    [Security.MasterKey.Exec](../../server/configuration/security-configuration#security.masterkey.exec)  
    [Security.MasterKey.Exec.Arguments](../../server/configuration/security-configuration#security.masterkey.exec.arguments)  
    [Security.MasterKey.Exec.TimeoutInSec](../../server/configuration/security-configuration#security.masterkey.exec.timeoutinsec)  
    [Security.MasterKey.Path](../../server/configuration/security-configuration#security.masterkey.path)
  * Security.TwoFactor:  
    [Security.TwoFactor.DefaultSessionDurationInMin](../../server/configuration/security-configuration#security.twofactor.defaultsessiondurationinmin)  
    [Security.TwoFactor.MaxSessionDurationInMin](../../server/configuration/security-configuration#security.twofactor.maxsessiondurationinmin)
  * Certificate and issuer validation:  
    [Security.WellKnownCertificates.Admin](../../server/configuration/security-configuration#security.wellknowncertificates.admin)  
    [Security.WellKnownIssuerHashes.Admin](../../server/configuration/security-configuration#security.wellknownissuerhashes.admin)  
    [Security.WellKnownIssuers.Admin](../../server/configuration/security-configuration#security.wellknownissuers.admin)  
    [Security.WellKnownIssuers.Admin.ValidateCertificateNames](../../server/configuration/security-configuration#security.wellknownissuers.admin.validatecertificatenames)
  * Other:  
    [Security.DisableHsts](../../server/configuration/security-configuration#security.disablehsts)  
    [Security.DisableHttpsRedirection](../../server/configuration/security-configuration#security.disablehttpsredirection)  
    [Security.DoNotConsiderMemoryLockFailureAsCatastrophicError](../../server/configuration/security-configuration#security.donotconsidermemorylockfailureascatastrophicerror)  
    [Security.TlsCipherSuites](../../server/configuration/security-configuration#security.tlsciphersuites)  
    [Security.UnsecuredAccessAllowed](../../server/configuration/security-configuration#security.unsecuredaccessallowed)

{NOTE/}

---

{PANEL: Security.AuditLog.EnableArchiveFileCompression}

Determines whether to compress the audit log files.

- **Type**: `bool`
- **Default**: `false`
- **Scope**: Server-wide only
- **Alias:** `Security.AuditLog.Compress`

{PANEL/}

{PANEL: Security.AuditLog.FolderPath}

The folder path where RavenDB stores audit log files.  
Setting the path enables writing to the audit log.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.AuditLog.ArchiveAboveSizeInMb}

The largest size (in megabytes) that an audit log file may reach 
before it is archived and logging is directed to a new file.

- **Type**: `Size`
- **Default**: `128`
- **MinValue**: `16`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.AuditLog.MaxArchiveDays}

The maximum number of days that an archived audit log file is kept.  

- **Type**: `int?`
- **Default**: `3`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.AuditLog.MaxArchiveFiles}

The maximum number of archived audit log files to keep.  
Set this value to the number of days after which audit log files will be deleted,  
or set it to `null` to refrain from removing audit log files.  

- **Type**: `int?`
- **Default**: `null`
- **Min Value**: `0`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Change.Exec}

A command or executable that handles cluster certificate changes.  
This executable allows you to implement your own custom logic for persisting the new certificate on all nodes.

Note: it will only be triggered if [Security.Certificate.Path](../../server/configuration/security-configuration#security.certificate.path) is not defined.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Change.Exec.Arguments}

The command line arguments for the [Security.Certificate.Change.Exec](../../server/configuration/security-configuration#security.certificate.change.exec) command or executable.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Exec}

Deprecated.  
Use [Security.Certificate.Load.Exec](../../server/configuration/security-configuration#security.certificate.load.exec)
along with [Security.Certificate.Renew.Exec](../../server/configuration/security-configuration#security.certificate.renew.exec)
and [Security.Certificate.Change.Exec](../../server/configuration/security-configuration#security.certificate.change.exec) instead.

{PANEL/}

{PANEL: Security.Certificate.Exec.TimeoutInSec}

* The number of seconds to wait for the certificate executables to exit.
* Applies to:
  * [Security.Certificate.Load.Exec](../../server/configuration/security-configuration#security.certificate.load.exec)
  * [Security.Certificate.Renew.Exec](../../server/configuration/security-configuration#security.certificate.renew.exec)
  * [Security.Certificate.Change.Exec](../../server/configuration/security-configuration#security.certificate.change.exec)

---

- **Type**: `int`
- **Default**: `30`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.ExpiringThresholdInDays}

The number of days before certificate expiration when it will be considered _expiring_.

- **Type**: `int`
- **Default**: `14`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.LetsEncrypt.Email}

The E-mail address associated with the Let's Encrypt certificate.  
Used for renewal requests.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Load.Exec}

* A command or executable that provides the `.pfx` cluster certificate when invoked by RavenDB.  
  If specified, RavenDB will use HTTPS/SSL for all network activities.

* The [Security.Certificate.Path](../../server/configuration/security-configuration#security.certificate.path) setting takes precedence over this executable.

* Learn more in [get certificate via loader](../../server/security/authentication/certificate-configuration#with-logic-foreign-to-ravendb-or-external-certificate-storage).

---

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Load.Exec.Arguments}

The command line arguments for the [Security.Certificate.Load.Exec](../../server/configuration/security-configuration#security.certificate.load.exec) command or executable.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Password}

The (optional) password of the .pfx certificate file.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Path}

The path to the `.pfx` certificate file. If specified, RavenDB will use HTTPS/SSL for all network activities.  
Certificate setting priority order:
1. Path
2. Executable

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Renew.Exec}

* A command or executable that handles automatic renewals, providing a renewed `.pfx` cluster certificate.

* The [leader node](../../server/clustering/rachis/cluster-topology#leader) will invoke this executable once every hour, and if a new certificate is received,  
  it will be sent to all other nodes.

* The executable specified in [Security.Certificate.Change.Exec](../../server/configuration/security-configuration#security.certificate.change.exec)
  will then be used to persist the certificate across the cluster on all nodes.

---

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Renew.Exec.Arguments}

The command line arguments for the [Security.Certificate.Renew.Exec](../../server/configuration/security-configuration#security.certificate.renew.exec.arguments) command or executable.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Validation.Exec}

EXPERT ONLY:

A command or executable to validate a server authentication request.  
RavenDB will execute: `command [user-arg-1] ... [user-arg-n] <sender-url> <base64-certificate> <errors>`.

The executable will return a case-insensitive boolean string through the standard output (e.g. true, false) indicating whether to approve the connection.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Validation.Exec.Arguments}

EXPERT ONLY:

The optional user arguments for the [Security.Certificate.Validation.Exec](../../server/configuration/security-configuration#security.certificate.validation.exec.arguments) command or executable.  
The arguments must be escaped for the command line.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Validation.Exec.TimeoutInSec}

The number of seconds to wait for the [Security.Certificate.Validation.Exec](../../server/configuration/security-configuration#security.certificate.validation.exec.arguments) executable to exit.

- **Type**: `int`
- **Default**: `5`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Certificate.Validation.KeyUsages}

EXPERT ONLY:

Indicates if 'KeyUsage' validation of certificates should be turned on or off.

- **Type**: `bool`
- **Default**: `true`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Csrf.AdditionalOriginHeaders}

Specify additional request headers that RavenDB will check for the Origin of a request.  
For example: `X-Forwarded-Host`.

- **Type**: `string[]`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Csrf.Enabled}

Indicates whether the Cross-Site Request Forgery (CSRF) protection is enabled in RavenDB.

- **Type**: `bool`
- **Default**: `true`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.Csrf.TrustedOrigins}

List of Trusted Origins for CSRF filter.  
Requests from these origins will be allowed without triggering CSRF checks.

- **Type**: `string[]`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.MasterKey.Exec}

A command or executable that RavenDB will run to obtain a 256-bit Master Key.   
If specified, RavenDB will use this key to protect secrets.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.MasterKey.Exec.Arguments}

The command line arguments for the [Security.MasterKey.Exec](../../server/configuration/security-configuration#security.masterkey.exec) command or executable.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.MasterKey.Exec.TimeoutInSec}

The number of seconds to wait for the Master Key executable to exit.

- **Type**: `int`
- **Default**: `30`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.MasterKey.Path}

The file path to a (256-bit) Master Key.  
If specified, RavenDB will use this key to protect secrets.

- **Type**: `string`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.TwoFactor.DefaultSessionDurationInMin}

The default duration of a two-factor authentication (2FA) session, in minutes.

After successfully completing the 2FA process, the session will remain active for this duration before requiring re-authentication.

- **Type**: `int`
- **Default**: `120`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.TwoFactor.MaxSessionDurationInMin}

The maximum duration of a two-factor authentication (2FA) session, in minutes.  
This duration takes precedence over the default duration setting.

- **Type**: `int`
- **Default**: `1440`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.WellKnownCertificates.Admin}

Specify well-known certificate thumbprints that will be trusted by the server as cluster admins.

- **Type**: `string[]` or `string with thumbprints values separated by ;`
- **Example**: `"297430d6d2ce259772e4eccf97863a4dfe6b048c;e6a3b45b062d509b3382282d196efe97d5956ccb"`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.WellKnownIssuerHashes.Admin}

OBSOLETE.  
This is no longer supported or used.  
Use [Security.WellKnownIssuers.Admin](../../server/configuration/security-configuration#security.wellknownissuers.admin) instead.

{PANEL/}

{PANEL: Security.WellKnownIssuers.Admin}

Specify well-known issuer certificates in Base64 format or provide file paths to the certificate files.  
This will be used to validate a new client certificate when the issuer's certificate changes.

- **Type**: `string[]` or `string with values separated by ;`
- **Default**: `null`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.WellKnownIssuers.Admin.ValidateCertificateNames}

Determine whether the server will validate the subject alternative names (SANs) of well-known issuer certificates against the server's domain name.

- **Type**: `bool`
- **Default**: `false`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.DisableHsts}

Disable HTTP Strict Transport Security (HSTS) on the server.

- **Type**: `bool`
- **Default**: `false`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.DisableHttpsRedirection}

Disable automatic redirection when listening to HTTPS.  
By default, when using port 443, RavenDB redirects all incoming HTTP traffic on port 80 to HTTPS on port 443.

- **Type**: `bool`
- **Default**: `false`
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.DoNotConsiderMemoryLockFailureAsCatastrophicError}

EXPERT ONLY:

Determines whether RavenDB will consider memory lock error to be catastrophic.
This is used with encrypted databases to ensure that temporary buffers are never written to disk and are locked to memory.

Setting this to true is **not** recommended and should be done only after proper security analysis has been performed.

- **Type**: `bool`
- **Default**: `false`
- **Scope**: Server-wide or per database

{PANEL/}

{PANEL: Security.TlsCipherSuites}

EXPERT ONLY:

Defines a list of supported TLS Cipher Suites.  
Values must be semicolon-separated.

- **Type**: `TlsCipherSuite[]`
- **Example**: `TLS_RSA_WITH_RC4_128_MD5;TLS_RSA_WITH_RC4_128_SHA`
- **Default**: `null` (Operating System defaults)
- **Scope**: Server-wide only

{PANEL/}

{PANEL: Security.UnsecuredAccessAllowed}

If authentication is disabled, set the address range type for which server access is unsecured  
(`None | Local | PrivateNetwork | PublicNetwork`).

- **Type**: `enum UnsecuredAccessAddressRange`
- **Default**: `Local`
- **Scope**: Server-wide only

{PANEL/}
