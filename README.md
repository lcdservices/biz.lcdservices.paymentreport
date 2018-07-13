# biz.lcdservices.paymentreport

![Screenshot](/images/screenshot.png)

This extension installs a Payment Report template which can be used to generate lists of payment records attached to contributions. This is particularly helpful if your organization frequently has multiple payments linked to a single contribution and needs to be able to report on them apart from the parent contribution.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v5.4+
* CiviCRM 4.7+

## Installation (Web UI)

This extension has not yet been published for installation via the web UI.

## Installation (CLI, Zip)

Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl biz.lcdservices.paymentreport@https://github.com/FIXME/biz.lcdservices.paymentreport/archive/master.zip
```

## Installation (CLI, Git)

Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/FIXME/biz.lcdservices.paymentreport.git
cv en paymentreport
```

## Usage

Navigate to Reports, Contribution Reports, and select New Contribution Report. Locate the Payment Report and click to create a new report from that template. As with any report, you can create (save) a new report after selecting your desired configuration options.
