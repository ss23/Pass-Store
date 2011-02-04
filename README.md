Pass Store
==========

Open-source, self hosted, server software to store shared company passwords (and other password-like data).

Want to store communal passwords securely? Pass Store does just that. Create users that can add passwords, delete passwords, and edit passwords. You're in control of who can do what, and you get to see exactly who does what.
Best of all, this is all done with security the focus above all else.

Install
-------

Create the MySQL database. Fill in /config/config.php.
Run scripts/install.php and follow the instructions
Navigate to /html in your browser, and log in with the username and password you gave.

Usage
-----

Simply add new password's as you like, and view them in the index.

Upcoming Features
-----------------

* User Documentation
* Themes
* Modules
* Logging
* Generate a one time use session decryption key when users log in, destroy it on log out
* Secure notes
* YubiKey Support
* Secure File Upload/sharing
* Groups/User Permissions management
* Some kind of messaging system (WAY in the future)
* New Users on the fly
* Remove users
* User profiles (WAY in the future)

Why not just use X?
-------------------

If you find a product that does what this is planning to do, feel free to let me know so I can <strike>steal code and ideas</strike> consider it as a valid alternative.
In the mean time, people have suggested KeePass and LastPass as "alternatives".
KeePass isn't suited to a group of 5+ people, all having access to the same, live, database (without having to worry about locking issues), with fine grained permissions, groups, IDS, auditing, and easily configured access control through a webserver. However, as far as I'm aware, it meets the criteria of storing the passwords properly. Another thing to note is that it's not opensource, which could be a minus to some people, but is a plus to most.
LastPass, while it might meet the criteria of storing passwords, isn't made for corporate usage at *all*. Quite simply, it is a one user product, which is fine, it does an amazing job, and I wouldn't mind borrowing a lot of their ideas and UI inovations if I could. However, this simply isn't a solution.
