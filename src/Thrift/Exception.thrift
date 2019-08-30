namespace java com.skletter.Exception
namespace php Skletter.Model.RemoteService.Exception

exception NonExistentUser
{
  1: string	message
  2: i16	code
 }


exception UserExists
{
  1: string message
  2: i16	code
}

exception ValidationError
{
    1: string message
    2: i16    code
}