#!/usr/bin/env python3

# these hex strings were retrieved using `identify -verbose *png | grep comment:`
hex_strings = [
    "0b6230db558118b1fec2fc25886eb017a2255f6464dc2ca420061d1d90968c07aab02b7dd91b990709cdcc3232ad811607096dbe87967a2e4c4a8e8063347585ede757f98000d50cac9cbfe9a8e3ff24c7eb1170b85f4255bb3a3307937c7707",
    "8fd2604fe1f8fedda317d0cf9d511855a8a46d6bc5035fb2f2231a8c25b2844ebed4df5ce1d0bd1632d75c7bb4dc7ca8f16593dacd00aba539b3692aecfbb7730555ba7989b3a1efd24f32cc8f88342db5ebf1099dde0176a225c42693872736",
    "a2dc9c847c41d04cef6702b972feaf1b9e801788d010048f090fc73529d700d1ba99136870105cef367f9bbd78aecc93061dd9a7447d371dc629e2a52c71fb9c74c33ba2a62b72c11060b3e39fa163c8d908a24fd9bc57510caa135f06130c43",
    "d5b6b130f04a14ffabfc9dcb5095b8d23cf88a03c8b9a572ec74b128a9db3333d5c642c299dcc98cdc17adbf28f7a088c099bbee2664b5c16a00571afd00fccc34a344e87f675ea613bf858356d10864e63feaea1263e637335e5ab3d88f0cc0",
    "75bdfbbe1c2e465af490e8d68c6b48ebbca2b8ef68216140cd52ee4e5aac8ff5b86ddf5da5d66c28fb775b8240a72ee5ed48dd04e89900475abae5ef0b2d17bd03ed3e081ac58b70be29aab80e84b44be5d659deb4443823dc35cf34ea437b4e",
    "7dd7816c3b2a2d36baa87417b3fc5a22bba88114815888454e072e12acd2653cd9aadc75402cd031fef410f8751742ede5b3d6c8ace7370e2ade9ab700339a63e420b9ca65e971306deffb51dc09d96f3d1f10fb91bf6a4ce1738232fb31f6a1",
    "b8dc32c994e2393ad39fff9ecdcf5f8a66e18c739cc72b272cdad883353dec769c3016fd2c47f01fccf0c26a836d49e7dd5c2c7e1f6a07526da56417530b462fd432af6a762a6ccf59fcef63eb93c176b80ea46d6d504a61e486cd2520e1c6b0",
    "54a8bbe6e3dc4d8718104b502009ce6d1afbcb80cfa603c43ae1ee71c5b3cbb25dce0077d81eec5ce37674d920529aaf0807669b9b02630b9eabacb50d8619ea98c0846ac33c457b491faf77d981b8bcc8261c4a12b1d268231ef1da05b760a3", 
]

bytes_list = [bytes.fromhex(s) for s in hex_strings]

# generate a message with ALL XORed together
before = bytes_list[0]
for item in range(1, len(bytes_list)):
    before = bytes(x ^ y for x, y in zip(before, bytes_list[item]))
    

# test XORing the message with each of the other messages (to negate it)
for message in bytes_list:
    after = bytes(x ^ y for x, y in zip(before, message))
    
    try:
        print(after.decode('utf8'))
        print("Hidden directory must be the unused message:")
        print(message.hex())
    except:
        ""