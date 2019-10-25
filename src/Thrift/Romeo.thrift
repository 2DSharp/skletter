namespace java com.skletter.Romeo
namespace php Skletter.Model.RemoteService.Romeo

include "DTO.thrift"
include "Exception.thrift"
typedef i32 int

service Romeo
{
    DTO.UserDTO registerNew(1: DTO.UserDTO user)
    DTO.CookieDTO loginWithPassword(1: string identifier, 2: string password, 3: DTO.LoginMetadata metaData)
    DTO.UserDTO verifyToken(1: int id, 2: string token)
    DTO.UserDTO verifyPin(1: int id, 2: string pin)
}