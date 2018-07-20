var BaseCrypt = {
    importCodes: [],
    exportCodes: [],
    specialCodes: ['#','&','%'],
    trashCodes: [],

    substr_count: function (string, symbol) {
        var c = 0;
        for (var i=0; i < string.length; i++)
        {
            if (symbol == string[i]) {
                c++;
            }
        }
        return c;
    },
    random: function(max) {
        return Math.floor(Math.random() * max);
    },

    inBits: function() {
        if (!this.importCodes) {
            throw 'Class must have a importCodes';
        }
        return Math.ceil(Math.log2(this.importCodes.length));
    },
    outBits: function() {
        if (!this.exportCodes) {
            throw 'Class must have a exportCodes';
        }
        return Math.ceil(Math.log2(this.exportCodes.length));
    },

    encode: function (string) {
        if (!this.importCodes) {
            throw 'Class must have a importCodes';
        }
        if (typeof(string) != 'string') {
            throw 'It is not a string!';
        }
        if (!string) { return ''; }

        var hStr = '';
        for (var i = 0; i < string.length; i++) {
            var symbol = string[i],
                index = this.importCodes.indexOf(symbol);
            if (index < 0) {
                throw "This character is not supported: " + symbol;
            }
            hStr += index.toString(2).padStart(this.inBits(), '0');
        }

        var shift = this.substr_count(hStr, '1');
        if (shift) {
            hStr = hStr.substr(-shift) + hStr.substr(0, hStr.length-shift);
        }

        var res = '',
            pos = 0,
            subStr;

        while (subStr = hStr.substr(pos, this.outBits())) {
            subStr = subStr.padEnd(this.outBits(), '0');
            pos += this.outBits();
            res += this.exportCodes[parseInt(subStr, 2)];
        }

        if (hStr.length % this.outBits()) {
            var needSpecialSymbols = ((((hStr.length/this.outBits()|0)+1)*this.outBits() - hStr.length) / this.inBits())|0;
            if (needSpecialSymbols >= 1) {
                for (i = 0; i < needSpecialSymbols; i++) {
                    pos = this.random(res.length + 1);
                    res = res.slice(0, pos) + this.specialCodes[this.random(this.specialCodes.length)] + res.slice(pos);
                }
            }
        }

        if (this.trashCodes.length) {
            var trashCount = Math.round( (hStr.length - shift) / this.outBits() );
            if (trashCount >= 1) {
                for (i = 0; i < trashCount; i++) {
                    pos = this.random(res.length + 1);
                    res = res.slice(0, pos) + this.trashCodes[this.random(this.trashCodes.length)] + res.slice(pos);
                }
            }
        }

        return res;
    },

    decode: function(string) {

        if (!this.importCodes) {
            throw 'Class must have a importCodes';
        }
        if (typeof(string) != 'string') {
            throw 'It is not a string!';
        }
        if (!string) { return ''; }

        var hStr = '',
            zerosCount,
            skipBlocks = 0;
        for (var i = 0; i < string.length; i++) {
            var symbol = string[i],
                index = this.exportCodes.indexOf(symbol);
            if (this.specialCodes.indexOf(symbol) > -1) {
                skipBlocks++;
                continue;
            }
            if (this.trashCodes.indexOf(symbol) > -1) {
                continue;
            }
            if (index < 0) {
                throw "This character is not supported: " + symbol;
            }

            hStr += index.toString(2).padStart(this.outBits(), '0');
        }

        if (zerosCount =  hStr.length % this.inBits()) {
            hStr = hStr.substr(0, hStr.length - zerosCount)
        }

        if (skipBlocks) {
            hStr = hStr.substr(0, hStr.length - skipBlocks*this.inBits())
        }

        var shift = this.substr_count(hStr, '1');
        if (shift) {
            hStr = hStr.substr(shift) + hStr.substr(0, shift);
        }

        var res = '',
            pos = 0,
            subStr;

        while (subStr = hStr.substr(pos, this.inBits())) {
            pos += this.inBits();
            res += this.importCodes[parseInt(subStr, 2)];
        }

        return res;
    }
};