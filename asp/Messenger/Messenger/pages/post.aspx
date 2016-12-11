<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="post.aspx.cs" MasterPageFile="~/layout.Master" Inherits="Messenger.pages.post" %>
<asp:Content ID="postsTitle" ContentPlaceHolderID="title" runat="server">
   <link rel="stylesheet" type="text/css" href="../public/dist/myStyle.css" />
   <!-- links to rescoures -->
   <link rel="icon", type="image/x-icon", href="../public/images/favicon.ico" />
   <link rel="shortcut icon", type="image/x-icon", href="../public/images/favicon.ico" />
   <title>Posts</title>
</asp:Content>
<asp:Content ID="postsPage" ContentPlaceHolderID="body" runat="server">
   <form id="postsForm" runat="server">
      <nav class="navbar navbar-light bg-faded">
         <ul class="nav navbar-nav">
            <li class="nav-item active">
               <asp:LinkButton ID="btnHome" CssClass="btn btn-primary" runat="server" OnClick="btnHome_Click">
                  <span class="glyphicon glyphicon-home"></span>
               </asp:LinkButton>
            </li>
            <li class="nav-item active">
               <a class="nav-link" href="#Posts">Posts</a>
            </li>
            <li class="nav-item active">
               <a class="nav-link" href="#Add">
               <span class="glyphicon glyphicon-plus-sign"></span>
               </a>
            </li>
            <li class="nav-item active">
               <a class="nav-link" href="#Members">Members</a>
            </li>
            <li class="nav-item active">
               <a class="nav-link" href="#Data">Data</a>
            </li>
             <li class="nav-item">
               <a class="nav-link" href="#User">
                   <asp:Label ID="lblError" runat="server" Text=""></asp:Label>
               </a>
            </li>
         </ul>
      </nav>
      <section>
         <asp:SqlDataSource ID="sqlMembers" runat="server" ConnectionString='<%$ ConnectionStrings:Messenger2 %>' ProviderName='<%$ ConnectionStrings:Messenger2.ProviderName %>' SelectCommand="SELECT [fName]+ '   ' + [lName] AS fullPerson FROM person INNER JOIN (groups INNER JOIN people_group ON groups.groups_id = people_group.groups_id) ON person.person_id = people_group.person_id WHERE ([groups.name] = ?) AND ([people_group.message] Is Null) ORDER BY people_group.posted DESC">
            <SelectParameters>
               <asp:SessionParameter Name="name" SessionField="name" Type="String" />
            </SelectParameters>
         </asp:SqlDataSource>
         <asp:SqlDataSource ID="sqlPosts" runat="server" ConnectionString='<%$ ConnectionStrings:Messenger2 %>' ProviderName='<%$ ConnectionStrings:Messenger2.ProviderName %>' SelectCommand="select *,  [fName]+ '   ' + [lName] AS fullPerson FROM ((person INNER JOIN people_group ON person.person_id = people_group.person_id) INNER JOIN groups ON people_group.groups_id = groups.groups_id) WHERE ([groups.name] = ?) AND ([people_group.posted] Is Not Null) AND (people_group.[message] Is Not Null) order by posted">
            <SelectParameters>
               <asp:SessionParameter Name="name" SessionField="name" Type="String" />
            </SelectParameters>
         </asp:SqlDataSource>
         <asp:SqlDataSource ID="sqlChanger" runat="server" ConnectionString='<%$ ConnectionStrings:Messenger2 %>' ProviderName='<%$ ConnectionStrings:Messenger2.ProviderName %>'></asp:SqlDataSource>
         <article id="Posts">
            <asp:DataList ID="dlistPosts" runat="server" DataSourceID="sqlPosts">
               <ItemTemplate>
                  <div class="card">
                     <div class="card-block">
                        <h3 class="card-title">
                           <asp:Label Text='<%# Eval("fullPerson") %>' runat="server" id="lblFullName"/>
                          
                         <asp:Image ID="imgPersonPicture" runat="server" ImageUrl='<%# Eval("picture").ToString() != "" ? "../uploads/"+Eval("picture") : "../../public/images/default.jpg" %>' AlternateText="User picture" CssClass="curUserImage" />
         
                           
                        </h3>
                        <asp:Label Text='<%# Eval("message") %>' runat="server" id="lblMessage"/>
                        <br />
                         <%# Eval("video").ToString() != "" ? "<div align=\"center\" class=\"embed-responsive embed-responsive-16by9\">  <video autoplay loop class=\"embed-responsive-item\" controls>  <source src=\"../../uploads/"+Eval("video")+"\" type=\"video/mp4\"> </video> </div>  " : "" %>
   
                        <asp:Label Text='<%# Eval("posted") %>' runat="server" id="lblPosted"/>
                        <% if( Session["adim"] != null) { %>
                        <asp:Button ID="btnPostDelete" CssClass="btn btn-primary" runat="server" Text='<%# Eval("people_group_id") %>' OnClick="btnPostDelete_Click" />
                        <% } %>
                     </div>
                  </div>
               </ItemTemplate>
            </asp:DataList>
         </article>
         <article id="Add">
            <div id="postAdder">
               <div class="card">
                  <div class="card-block">
                     <h3 class="card-title">Added posts</h3>
                     <% System.Data.DataView dvSql = (System.Data.DataView)sqlMembers.Select(DataSourceSelectArguments.Empty);
                        if ((Session["adim"] != null) && (dvSql.Count == 0)) { %>
                     <h2>Sorry adim you need to add your self first</h2>
                     <% } else { %>
                     <div class="form-group row">
                        <label for="txtMessager" class="col-xs-2 col-form-label">Message</label>
                        <div class="col-xs-10">
                           <asp:TextBox ID="txtMessager" TextMode="multiline" CssClass="form-control message" runat="server" Rows="3"></asp:TextBox>
                        </div>
                     </div>
                      <div class="form-group">
                          <label class="custom-file">
  <asp:FileUpload ID="fileVideo"  CssClass="custom-file-input" runat="server" />
  <span class="custom-file-control"></span>
</label>
                      </div>
                      
                     <asp:Button ID="btnNewPost"  CssClass="btn btn-primary" runat="server" Text="Add" OnClick="btnNewPost_Click" />
                     <% } %>
                  </div>
               </div>
            </div>
         </article>
         <article id="Members">
             <div class="card">
                     <div class="card-block">
                        <h3 class="card-title">Members</h3>
            <asp:DataList ID="dListMembers" runat="server" DataSourceID="sqlMembers" CssClass="table">
               <ItemTemplate>
                  
                              <tr>
                                 <th scope="row">
                                    <asp:Label Text='<%# Eval("fullPerson") %>' runat="server" ID="lblMembersFullPerson" />
                                 </th>
                              </tr>
                  
              
                   
               </ItemTemplate>
            </asp:DataList>
                           </div>
                  </div>
         </article>
         <article id="Data">
            <div class="card">
               <div class="card-block">
                  <h3 class="card-title">Group data</h3>
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Name of group</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <th scope="row">
                              <asp:Label Text="Hi" runat="server" ID="lblGroupData" />
                           </th>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </article>
      </section>
   </form>
   <script src="../public/dist/my-com.js" type="text/javascript"></script>
</asp:Content>