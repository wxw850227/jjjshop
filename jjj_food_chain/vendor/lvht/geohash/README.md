# GeoHash

Simple php geohash class like python-geohash.


## Getting Started

### Install

  composer require lvht/geohash

### System Requirements
You need **PHP >= 5.4.0**.

### Usage
Encode a coordinate:

    use Lvht\GeoHash;
    echo GeoHash::encode(117.031689,36.65396);

The result is wwe0x0euu12.

The default precision is 0.00001 which can be changed by the third parameter
of encode method.

Find the neighbors for a given geohash:

    use Lvht\GeoHash;
    var_dump(GeoHash::expand('wwe0x0'));

and the result is:

    array(8) {
      [0] =>
      string(11) "wwe0wc7zzzz"
      [1] =>
      string(11) "wwe0x17zzzz"
      [2] =>
      string(11) "wwe0x37zzzz"
      [3] =>
      string(11) "wwe0wb7zzzz"
      [4] =>
      string(11) "wwe0x27zzzz"
      [5] =>
      string(11) "wwe0qz7zzzz"
      [6] =>
      string(11) "wwe0rp7zzzz"
      [7] =>
      string(11) "wwe0rr7zzzz"
    }

Decode a geohash string:

    Use Lvht\GeoHash;
    var_dump(GeoHash::decode('wwe0x0'));

and the result is:

    array(4) {
      [0] =>
      double(117.0263671875)    # min longitude
      [1] =>
      double(117.03735351562)   # max longitude
      [2] =>
      double(36.650390625)      # min latitude
      [3] =>
      double(36.655883789062)   # max latitude
    }
