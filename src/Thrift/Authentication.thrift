namespace java com.skletter.service.Authentication
namespace php Skletter.Model.RemoteService.Authentication

include "Exception.thrift"

typedef i32 int

typedef string DateTime

service Authentication
{
    void createStandardIdentity(1: int userId, 2: string password),
    string createCookieIdentity(1: int userId, 2: string userAgent),
    void createNonceTokenIdentity(1: int userId, 2: DateTime expiresOn, 3: string format),
    void createNoncePinIdentity(1: int userId, 2: DateTime expiresOn, 3: string format),

    bool authenticatePassword(1: int userId, 2: string password) throws (1: Exception.NonExistentUser nonexistentUser),
    bool authenticateCookie(1: string cookie, 2: string userAgent) throws (1: Exception.NonExistentUser nonexistentUser),
    bool authenticateNonceToken(1: int userId, 2: string token) throws (1: Exception.NonExistentUser nonexistentUser),
    bool authenticateNoncePin(1: int userId, 2: i16 pin) throws (1: Exception.NonExistentUser nonexistentUser),

    void updateStandardIdentity(1: int userId, 2: string password),
    void invalidateCookie(1: string cookie),
    void invalidateCookieByCookieId(1: int id),

    string getCookieIdFromCookie(1: string cookie);
}