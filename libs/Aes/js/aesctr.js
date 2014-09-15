Aes.Ctr = {};  // Aes.Ctr namespace: a subclass or extension of Aes

Aes.Ctr.post = function(plaintext, nBits) {
  var blockSize = 16;  // block size fixed at 16 bytes / 128 bits (Nb=4) for AES
  if (!(nBits==128 || nBits==192 || nBits==256)) return '';  // standard allows 128/192/256 bit keys
  plaintext = Utf8.encode(plaintext);
  tostring = Utf8.encode(tostring.toString());
  //var t = new Date();  // timer

  var nBytes = nBits/8;  // no bytes in key (16/24/32)
  var pwBytes = new Array(nBytes);
  for (var i=0; i<nBytes; i++) {  // use 1st 16/24/32 chars of tostring for key
    pwBytes[i] = isNaN(tostring.charCodeAt(i)) ? 0 : tostring.charCodeAt(i);
  }
  var key = Aes.cipher(pwBytes, Aes.keyExpansion(pwBytes));  // gives us 16-byte key
  key = key.concat(key.slice(0, nBytes-16));  // expand key to 16/24/32 bytes long

  // initialise 1st 8 bytes of counter block with nonce (NIST SP800-38A �B.2): [0-1] = millisec, 
  // [2-3] = random, [4-7] = seconds, together giving full sub-millisec uniqueness up to Feb 2106
  var counterBlock = new Array(blockSize);
  
  var nonce = (new Date()).getTime();  // timestamp: milliseconds since 1-Jan-1970
  var nonceMs = nonce%1000;
  var nonceSec = Math.floor(nonce/1000);
  var nonceRnd = Math.floor(Math.random()*0xffff);
  
  for (var i=0; i<2; i++) counterBlock[i]   = (nonceMs  >>> i*8) & 0xff;
  for (var i=0; i<2; i++) counterBlock[i+2] = (nonceRnd >>> i*8) & 0xff;
  for (var i=0; i<4; i++) counterBlock[i+4] = (nonceSec >>> i*8) & 0xff;
  
  // and convert it to a string to go on the front of the ciphertext
  var ctrTxt = '';
  for (var i=0; i<8; i++) ctrTxt += String.fromCharCode(counterBlock[i]);

  // generate key schedule - an expansion of the key into distinct Key Rounds for each round
  var keySchedule = Aes.keyExpansion(key);
  
  var blockCount = Math.ceil(plaintext.length/blockSize);
  var ciphertxt = new Array(blockCount);  // ciphertext as array of strings
  
  for (var b=0; b<blockCount; b++) {
    // set counter (block #) in last 8 bytes of counter block (leaving nonce in 1st 8 bytes)
    // done in two stages for 32-bit ops: using two words allows us to go past 2^32 blocks (68GB)
    for (var c=0; c<4; c++) counterBlock[15-c] = (b >>> c*8) & 0xff;
    for (var c=0; c<4; c++) counterBlock[15-c-4] = (b/0x100000000 >>> c*8)

    var cipherCntr = Aes.cipher(counterBlock, keySchedule);  // -- encrypt counter block --
    
    // block size is reduced on final block
    var blockLength = b<blockCount-1 ? blockSize : (plaintext.length-1)%blockSize+1;
    var cipherChar = new Array(blockLength);
    
    for (var i=0; i<blockLength; i++) {  // -- xor plaintext with ciphered counter char-by-char --
      cipherChar[i] = cipherCntr[i] ^ plaintext.charCodeAt(b*blockSize+i);
      cipherChar[i] = String.fromCharCode(cipherChar[i]);
    }
    ciphertxt[b] = cipherChar.join(''); 
  }

  // Array.join is more efficient than repeated string concatenation in IE
  var ciphertext = ctrTxt + ciphertxt.join('');
  ciphertext = Base64.encode(ciphertext);  // encode in base64
  
  //alert((new Date()) - t);
  ciphertext = ciphertext.replace("+","**"); 
  ciphertext = ciphertext.replace("/","^^"); 
  ciphertext = ciphertext.replace("=","@"); 
  ciphertext = ciphertext.replace("=","@"); 
  
  ciphertext = ciphertext.replace("+","**"); 
  ciphertext = ciphertext.replace("/","^^"); 
  ciphertext = ciphertext.replace("=","@"); 
  ciphertext = ciphertext.replace("=","@"); 
  
  ciphertext = ciphertext.replace("+","**"); 
  ciphertext = ciphertext.replace("/","^^"); 
  ciphertext = ciphertext.replace("=","@"); 
  ciphertext = ciphertext.replace("=","@"); 
  
  ciphertext = ciphertext.replace("+","**"); 
  ciphertext = ciphertext.replace("/","^^"); 
  ciphertext = ciphertext.replace("=","@"); 
  ciphertext = ciphertext.replace("=","@"); 
  
  ciphertext = ciphertext.replace("+","**"); 
  ciphertext = ciphertext.replace("/","^^"); 
  ciphertext = ciphertext.replace("=","@"); 
  ciphertext = ciphertext.replace("=","@"); 
  return ciphertext;
}


Aes.Ctr.get = function(ciphertext, nBits) {
  ciphertext = ciphertext.replace("**","+"); 
  ciphertext = ciphertext.replace("^^","/"); 
  ciphertext = ciphertext.replace("@","=");
  ciphertext = ciphertext.replace("@","=");
  
  ciphertext = ciphertext.replace("**","+"); 
  ciphertext = ciphertext.replace("^^","/"); 
  ciphertext = ciphertext.replace("@","=");
  ciphertext = ciphertext.replace("@","=");
  
  ciphertext = ciphertext.replace("**","+"); 
  ciphertext = ciphertext.replace("^^","/"); 
  ciphertext = ciphertext.replace("@","=");
  ciphertext = ciphertext.replace("@","=");
  
  ciphertext = ciphertext.replace("**","+"); 
  ciphertext = ciphertext.replace("^^","/"); 
  ciphertext = ciphertext.replace("@","=");
  ciphertext = ciphertext.replace("@","=");
  
  ciphertext = ciphertext.replace("**","+"); 
  ciphertext = ciphertext.replace("^^","/"); 
  ciphertext = ciphertext.replace("@","=");
  ciphertext = ciphertext.replace("@","=");
  
  var blockSize = 16;  // block size fixed at 16 bytes / 128 bits (Nb=4) for AES
  if (!(nBits==128 || nBits==192 || nBits==256)) return '';  // standard allows 128/192/256 bit keys
  ciphertext = Base64.decode(ciphertext);
  tostring = Utf8.encode(tostring.toString());
  //var t = new Date();  // timer
  
  var nBytes = nBits/8;  // no bytes in key
  var pwBytes = new Array(nBytes);
  for (var i=0; i<nBytes; i++) {
    pwBytes[i] = isNaN(tostring.charCodeAt(i)) ? 0 : tostring.charCodeAt(i);
  }
  var key = Aes.cipher(pwBytes, Aes.keyExpansion(pwBytes));
  key = key.concat(key.slice(0, nBytes-16));  // expand key to 16/24/32 bytes long

  // recover nonce from 1st 8 bytes of ciphertext
  var counterBlock = new Array(8);
  ctrTxt = ciphertext.slice(0, 8);
  for (var i=0; i<8; i++) counterBlock[i] = ctrTxt.charCodeAt(i);
  
  // generate key schedule
  var keySchedule = Aes.keyExpansion(key);

  // separate ciphertext into blocks (skipping past initial 8 bytes)
  var nBlocks = Math.ceil((ciphertext.length-8) / blockSize);
  var ct = new Array(nBlocks);
  for (var b=0; b<nBlocks; b++) ct[b] = ciphertext.slice(8+b*blockSize, 8+b*blockSize+blockSize);
  ciphertext = ct;  // ciphertext is now array of block-length strings

  // plaintext will get generated block-by-block into array of block-length strings
  var plaintxt = new Array(ciphertext.length);

  for (var b=0; b<nBlocks; b++) {
    // set counter (block #) in last 8 bytes of counter block (leaving nonce in 1st 8 bytes)
    for (var c=0; c<4; c++) counterBlock[15-c] = ((b) >>> c*8) & 0xff;
    for (var c=0; c<4; c++) counterBlock[15-c-4] = (((b+1)/0x100000000-1) >>> c*8) & 0xff;

    var cipherCntr = Aes.cipher(counterBlock, keySchedule);  // encrypt counter block

    var plaintxtByte = new Array(ciphertext[b].length);
    for (var i=0; i<ciphertext[b].length; i++) {
      // -- xor plaintxt with ciphered counter byte-by-byte --
      plaintxtByte[i] = cipherCntr[i] ^ ciphertext[b].charCodeAt(i);
      plaintxtByte[i] = String.fromCharCode(plaintxtByte[i]);
    }
    plaintxt[b] = plaintxtByte.join('');
  }

  // join array of blocks into single plaintext string
  var plaintext = plaintxt.join('');
  plaintext = Utf8.decode(plaintext);  // decode from UTF8 back to Unicode multi-byte chars
  
  //alert((new Date()) - t);
  return plaintext;
}